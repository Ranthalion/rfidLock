#!/usr/bin/env python2.7
import RPIO
import serial
import sqlite3
import time

port = serial.Serial("/dev/ttyAMA0", baudrate=9600, timeout=3.0)
RPIO.setmode(RPIO.BCM)

def lock():
    global lock_status
    RPIO.output(7,True)
    time.sleep(1)
    RPIO.output(7,False)
    lock_status = 1


def unlock():
    global lock_status
    RPIO.output(8,True)
    time.sleep(1)
    RPIO.output(8,False)
    lock_status = 0


def lock_button_callback(gpio_id, value):
    print "falling edge detected on 23"
    #send lock signal to door
    lock()


def serial_callback(gpio_id, value):
    rcv = port.read(16)
    if rcv != '':
        print rcv
        #removing whitespace characters coming from rdif reader
        x = rcv[1:13]
        rfid = (x,)
        #check db for user
        conn = sqlite3.connect('hackrva.db')
        cursor = conn.cursor()
        cursor.execute('SELECT active FROM users WHERE rfid = ?',rfid)
        active = cursor.fetchone()[0]
        if active:
            #send unlock signal to door
            print 'unlocking'
            unlock()
        else:
            print 'no active user found'
# GPIO 23 & 17 set up as inputs, pulled up to avoid false detection.
# 23 works as the input for the button
# 17 is a gpio tied to the serial output of the rfid reader.the interrupt for
# that pinwill be used to determine when the reader starts transmitting
# could not configure pin 10 as the serial port and allow it to interrupt
# 8 is set as the output to trigure unlocking the door
# 7 is set as the output to trigure locking the door
RPIO.add_interrupt_callback(gpio_id=23, callback=lock_button_callback, edge='falling',
                            debounce_timeout_ms=100, threaded_callback=False,
                            pull_up_down=RPIO.PUD_UP)
RPIO.add_interrupt_callback(gpio_id=17, callback=serial_callback, edge='both',
                            threaded_callback=False, pull_up_down=RPIO.PUD_UP)
RPIO.setup(7,RPIO.OUT, initial=RPIO.LOW)
RPIO.setup(8,RPIO.OUT, initial=RPIO.LOW)


lock_status = -1
# now we'll define two threaded callback functions
# these will run in another thread when our events are detected




try:
    RPIO.wait_for_interrupts()



except KeyboardInterrupt:
    RPIO.cleanup()       # clean up RPIO on CTRL+C exit
RPIO.cleanup()           # clean up RPIO on normal exit
