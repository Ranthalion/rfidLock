import RPIO
import serial
import time

# I haven't had access to the reader hardware so I can't really test this live
# Should create some stubbed out tests though

class Door(object):
  def __init__(
      self,
      door_connection,
      port = serial.Serial("/dev/ttyAMA0", baudrate = 9600, timeout = 3.0),
      lock_pin = 7, # 7 is set as the output to trigure locking the door
      unlock_pin = 8, # 8 is set as the output to trigure unlocking the door
      start_tx_pin = 17, # 17 is a gpio tied to the serial output of the rfid reader.the interrupt for
      lock_button_pin = 23 # 23 works as the input for the button
      ):
    # start_tx_pin and lock_button_pin set up as inputs, pulled up to avoid false detection.
    # start_tx_pin is used to determine when the reader starts transmitting
    # could not configure pin 10 as the serial port and allow it to interrupt
    self.door_connection = door_connection
    self.lock_status = -1
    self.port = port
    self.lock_pin = lock_pin
    self.unlock_pin = unlock_pin
    self.start_tx_pin = start_tx_pin
    self.lock_button_pin = lock_button_pin
    self.connection = door_connection
  def run(self):
    RPIO.setmode(RPIO.BCM)
    # now we'll define two threaded callback functions
    # these will run in another thread when our events are detected
    RPIO.add_interrupt_callback(
        gpio_id=self.lock_button_pin,
        callback=self.lock_button_cb,
        edge='falling',
        debounce_timeout_ms=100,
        threaded_callback=False,
        pull_up_down=RPIO.PUD_UP)
    RPIO.add_interrupt_callback(
        gpio_id=self.start_tx_pin,
        callback=self.serial_cb,
        edge='both',
        threaded_callback=False,
        pull_up_down=RPIO.PUD_UP)
    RPIO.setup(self.lock_pin, RPIO.OUT, initial=RPIO.LOW)
    RPIO.setup(self.unlock_pin, RPIO.OUT, initial=RPIO.LOW)
    try:
      RPIO.wait_for_interrupts()
    except KeyboardInterrupt:
      # Could we just use finally here instead?
      # Uncaught exceptions will leave RPIO uncleaned, is this intentional?
      RPIO.cleanup() # clean up RPIO on CTRL+C exit
    RPIO.cleanup()   # clean up RPIO on normal exit
  def lock(self):
    RPIO.output(self.lock_pin,True)
    time.sleep(1)
    RPIO.output(self.lock_pin,False)
    self.lock_status = 1
  def unlock(self):
    RPIO.output(self.unlock_pin,True)
    time.sleep(1)
    RPIO.output(self.unlock_pin,False)
    self.lock_status = 0
  def lock_button_cb(self, gpio_id, value):
    print("falling edge detected on 23")
    #send lock signal to door
    self.lock()
  def serial_cb(self, gpio_id, value):
    rcv = self.port.read(16)
    if rcv != '':
      #removing whitespace characters coming from rdif reader
      x = rcv[1:13]
      print(x)
      #check db for user
      # This is the part swapped in
      if self.door_connection.check_request(x):
        print 'unlocking'
        self.unlock()
