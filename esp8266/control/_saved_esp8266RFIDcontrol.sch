EESchema Schematic File Version 2
LIBS:power
LIBS:device
LIBS:switches
LIBS:relays
LIBS:motors
LIBS:transistors
LIBS:conn
LIBS:linear
LIBS:regul
LIBS:74xx
LIBS:cmos4000
LIBS:adc-dac
LIBS:memory
LIBS:xilinx
LIBS:microcontrollers
LIBS:dsp
LIBS:microchip
LIBS:analog_switches
LIBS:motorola
LIBS:texas
LIBS:intel
LIBS:audio
LIBS:interface
LIBS:digital-audio
LIBS:philips
LIBS:display
LIBS:cypress
LIBS:siliconi
LIBS:opto
LIBS:atmel
LIBS:contrib
LIBS:valves
LIBS:RTE24005F
LIBS:tps561208
LIBS:lolinesp8266
LIBS:esp8266RFIDcontrol-cache
EELAYER 25 0
EELAYER END
$Descr A4 11693 8268
encoding utf-8
Sheet 1 1
Title ""
Date ""
Rev ""
Comp ""
Comment1 ""
Comment2 ""
Comment3 ""
Comment4 ""
$EndDescr
$Comp
L +12V #PWR1
U 1 1 5B1DBAB8
P 1200 1350
F 0 "#PWR1" H 1200 1200 50  0001 C CNN
F 1 "+12V" H 1200 1490 50  0000 C CNN
F 2 "" H 1200 1350 50  0001 C CNN
F 3 "" H 1200 1350 50  0001 C CNN
	1    1200 1350
	1    0    0    -1  
$EndComp
$Comp
L +5V #PWR11
U 1 1 5B1DBAD4
P 5050 2300
F 0 "#PWR11" H 5050 2150 50  0001 C CNN
F 1 "+5V" H 5050 2440 50  0000 C CNN
F 2 "" H 5050 2300 50  0001 C CNN
F 3 "" H 5050 2300 50  0001 C CNN
	1    5050 2300
	1    0    0    -1  
$EndComp
$Comp
L +3.3V #PWR2
U 1 1 5B1DBAE8
P 1450 3650
F 0 "#PWR2" H 1450 3500 50  0001 C CNN
F 1 "+3.3V" H 1450 3790 50  0000 C CNN
F 2 "" H 1450 3650 50  0001 C CNN
F 3 "" H 1450 3650 50  0001 C CNN
	1    1450 3650
	1    0    0    -1  
$EndComp
$Comp
L LED D3
U 1 1 5B1DBBEB
P 2050 3750
F 0 "D3" V 2050 3850 50  0000 C CNN
F 1 "LED_WIFI" H 2050 3650 50  0000 C CNN
F 2 "LEDs:LED_0603_HandSoldering" H 2050 3750 50  0001 C CNN
F 3 "" H 2050 3750 50  0001 C CNN
	1    2050 3750
	-1   0    0    1   
$EndComp
$Comp
L R R1
U 1 1 5B1DBE33
P 1650 3750
F 0 "R1" V 1730 3750 50  0000 C CNN
F 1 "460" V 1650 3750 50  0000 C CNN
F 2 "Resistors_SMD:R_0805_HandSoldering" V 1580 3750 50  0001 C CNN
F 3 "" H 1650 3750 50  0001 C CNN
	1    1650 3750
	0    1    1    0   
$EndComp
$Comp
L R R6
U 1 1 5B1DBFC4
P 3750 3750
F 0 "R6" V 3830 3750 50  0000 C CNN
F 1 "1k" V 3750 3750 50  0000 C CNN
F 2 "Resistors_SMD:R_0805_HandSoldering" V 3680 3750 50  0001 C CNN
F 3 "" H 3750 3750 50  0001 C CNN
	1    3750 3750
	0    1    1    0   
$EndComp
$Comp
L Q_PNP_CBE Q1
U 1 1 5B1DC12A
P 4100 3750
F 0 "Q1" H 4300 3800 50  0000 L CNN
F 1 "Q_PNP_CBE" H 4300 3700 50  0000 L CNN
F 2 "TO_SOT_Packages_SMD:SOT-23_Handsoldering" H 4300 3850 50  0001 C CNN
F 3 "" H 4100 3750 50  0001 C CNN
	1    4100 3750
	1    0    0    -1  
$EndComp
$Comp
L +3.3V #PWR8
U 1 1 5B1DC1E7
P 4200 3550
F 0 "#PWR8" H 4200 3400 50  0001 C CNN
F 1 "+3.3V" H 4200 3690 50  0000 C CNN
F 2 "" H 4200 3550 50  0001 C CNN
F 3 "" H 4200 3550 50  0001 C CNN
	1    4200 3550
	1    0    0    -1  
$EndComp
$Comp
L GND #PWR9
U 1 1 5B1DC26C
P 4200 5150
F 0 "#PWR9" H 4200 4900 50  0001 C CNN
F 1 "GND" H 4200 5000 50  0000 C CNN
F 2 "" H 4200 5150 50  0001 C CNN
F 3 "" H 4200 5150 50  0001 C CNN
	1    4200 5150
	1    0    0    -1  
$EndComp
$Comp
L Conn_01x02 J2
U 1 1 5B1DC77D
P 5300 4200
F 0 "J2" H 5300 4300 50  0000 C CNN
F 1 "C_STRIKE" H 5300 4000 50  0000 C CNN
F 2 "Connectors_Terminal_Blocks:TerminalBlock_bornier-2_P5.08mm" H 5300 4200 50  0001 C CNN
F 3 "" H 5300 4200 50  0001 C CNN
	1    5300 4200
	1    0    0    -1  
$EndComp
Text GLabel 3450 3750 0    60   Input ~ 0
D6
Text GLabel 2450 3750 2    60   Input ~ 0
D3
Wire Wire Line
	2200 3750 2450 3750
$Comp
L SW_SPST SW1
U 1 1 5B1DD648
P 2050 4150
F 0 "SW1" H 2050 4275 50  0000 C CNN
F 1 "SW_RESET" H 2050 4050 50  0000 C CNN
F 2 "button_smd_2_pin:button_smd_2_pin" H 2050 4150 50  0001 C CNN
F 3 "" H 2050 4150 50  0001 C CNN
	1    2050 4150
	1    0    0    -1  
$EndComp
Text GLabel 2450 4150 2    60   Input ~ 0
D1
Wire Wire Line
	2250 4150 2450 4150
$Comp
L R R4
U 1 1 5B1DD76B
P 1600 4150
F 0 "R4" V 1680 4150 50  0000 C CNN
F 1 "1k" V 1600 4150 50  0000 C CNN
F 2 "Resistors_SMD:R_0805_HandSoldering" V 1530 4150 50  0001 C CNN
F 3 "" H 1600 4150 50  0001 C CNN
	1    1600 4150
	0    1    1    0   
$EndComp
$Comp
L GND #PWR3
U 1 1 5B1DD887
P 1450 4350
F 0 "#PWR3" H 1450 4100 50  0001 C CNN
F 1 "GND" H 1450 4200 50  0000 C CNN
F 2 "" H 1450 4350 50  0001 C CNN
F 3 "" H 1450 4350 50  0001 C CNN
	1    1450 4350
	1    0    0    -1  
$EndComp
Wire Wire Line
	1450 4150 1450 4350
Wire Wire Line
	1750 4150 1850 4150
$Comp
L Conn_01x02 J1
U 1 1 5B1DDD86
P 5300 2850
F 0 "J1" H 5300 2950 50  0000 C CNN
F 1 "C_DOOR" H 5300 2650 50  0000 C CNN
F 2 "jst:CONNECTOR_JST_SH_2" H 5300 2850 50  0001 C CNN
F 3 "" H 5300 2850 50  0001 C CNN
	1    5300 2850
	1    0    0    -1  
$EndComp
$Comp
L GND #PWR10
U 1 1 5B1DDDDD
P 4850 2950
F 0 "#PWR10" H 4850 2700 50  0001 C CNN
F 1 "GND" H 4850 2800 50  0000 C CNN
F 2 "" H 4850 2950 50  0001 C CNN
F 3 "" H 4850 2950 50  0001 C CNN
	1    4850 2950
	1    0    0    -1  
$EndComp
Text GLabel 4750 2850 0    60   Input ~ 0
D4
Wire Wire Line
	4750 2850 5100 2850
Wire Wire Line
	5100 2950 4850 2950
Text GLabel 4900 1350 0    55   Output ~ 0
D7
Text GLabel 4900 1250 0    55   Output ~ 0
D5
Text GLabel 4900 1550 0    55   Output ~ 0
D6
Text GLabel 4900 1650 0    55   Output ~ 0
D2
$Comp
L RTE24005F K1
U 1 1 5B1F1F2A
P 4400 4300
F 0 "K1" H 3997 4703 50  0000 L BNN
F 1 "RTE24005F" H 3998 3596 50  0000 L BNN
F 2 "esp-control:RTE24005F" H 4400 4300 50  0001 L BNN
F 3 "TE Connectivity" H 4400 4300 50  0001 L BNN
F 4 "None" H 4400 4300 50  0001 L BNN "Field4"
F 5 "https://www.digikey.com/product-detail/en/te-connectivity-potter-brumfield-relays/RTE24005F/PB295-ND/254512?utm_source=snapeda&utm_medium=aggregator&utm_campaign=symbol" H 4400 4300 50  0001 L BNN "Field5"
F 6 "PB295-ND" H 4400 4300 50  0001 L BNN "Field6"
F 7 "http://www.te.com/usa-en/product-4-1419108-0.html" H 4400 4300 50  0001 L BNN "Field7"
F 8 "RTE24005F" H 4400 4300 50  0001 L BNN "Field8"
F 9 "400 VAC" H 4400 4300 50  0001 L BNN "Field9"
F 10 "RTE24005F" H 4400 4300 50  0001 L BNN "Field10"
F 11 "RTE24005F _4-1419108-0_" H 4400 4300 50  0001 L BNN "Field11"
F 12 "8 A" H 4400 4300 50  0001 L BNN "Field12"
	1    4400 4300
	1    0    0    -1  
$EndComp
Wire Wire Line
	4200 3950 4200 4200
Wire Wire Line
	4200 4500 4200 5150
Wire Wire Line
	5100 4200 4800 4200
Wire Wire Line
	4600 4200 4600 4300
Wire Wire Line
	4600 4300 5100 4300
$Comp
L C C1
U 1 1 5B26C1C3
P 1200 1650
F 0 "C1" H 1225 1750 50  0000 L CNN
F 1 "10uF" H 1225 1550 50  0000 L CNN
F 2 "Capacitors_SMD:C_1210_HandSoldering" H 1238 1500 50  0001 C CNN
F 3 "" H 1200 1650 50  0001 C CNN
F 4 "25v" H 1200 1650 60  0001 C CNN "VDC"
	1    1200 1650
	1    0    0    -1  
$EndComp
$Comp
L C C2
U 1 1 5B26C235
P 2600 1400
F 0 "C2" V 2750 1350 50  0000 L CNN
F 1 "0.1uF" V 2450 1300 50  0000 L CNN
F 2 "Capacitors_SMD:C_1206_HandSoldering" H 2638 1250 50  0001 C CNN
F 3 "" H 2600 1400 50  0001 C CNN
F 4 "25v" V 2600 1400 60  0001 C CNN "VDC"
	1    2600 1400
	0    1    1    0   
$EndComp
$Comp
L C C3
U 1 1 5B26C283
P 3550 1800
F 0 "C3" H 3575 1900 50  0000 L CNN
F 1 "47uF" H 3575 1700 50  0000 L CNN
F 2 "Capacitors_SMD:C_1210_HandSoldering" H 3588 1650 50  0001 C CNN
F 3 "" H 3550 1800 50  0001 C CNN
F 4 "25v" H 3550 1800 60  0001 C CNN "VDC"
	1    3550 1800
	1    0    0    -1  
$EndComp
$Comp
L L L1
U 1 1 5B26C31E
P 3000 1400
F 0 "L1" V 2950 1400 50  0000 C CNN
F 1 "4.7uH" V 3075 1400 50  0000 C CNN
F 2 "Inductors_SMD:L_Wuerth_HCI-5040" H 3000 1400 50  0001 C CNN
F 3 "" H 3000 1400 50  0001 C CNN
F 4 "4.4A" V 3000 1400 60  0001 C CNN "IDC"
F 5 "0.025 Ohm" V 3000 1400 60  0001 C CNN "DCR"
	1    3000 1400
	0    -1   -1   0   
$EndComp
$Comp
L R R2
U 1 1 5B26C389
P 3300 1600
F 0 "R2" V 3380 1600 50  0000 C CNN
F 1 "54.9k" V 3300 1600 50  0000 C CNN
F 2 "Resistors_SMD:R_0805_HandSoldering" V 3230 1600 50  0001 C CNN
F 3 "" H 3300 1600 50  0001 C CNN
F 4 "1%" V 3300 1600 60  0001 C CNN "Tolerance"
	1    3300 1600
	1    0    0    -1  
$EndComp
$Comp
L R R3
U 1 1 5B26C41C
P 3300 2050
F 0 "R3" V 3380 2050 50  0000 C CNN
F 1 "10k" V 3300 2050 50  0000 C CNN
F 2 "Resistors_SMD:R_0805_HandSoldering" V 3230 2050 50  0001 C CNN
F 3 "" H 3300 2050 50  0001 C CNN
F 4 "1%" V 3300 2050 60  0001 C CNN "Tolerance"
	1    3300 2050
	1    0    0    -1  
$EndComp
$Comp
L GND #PWR6
U 1 1 5B26C47C
P 1950 2400
F 0 "#PWR6" H 1950 2150 50  0001 C CNN
F 1 "GND" H 1950 2250 50  0000 C CNN
F 2 "" H 1950 2400 50  0001 C CNN
F 3 "" H 1950 2400 50  0001 C CNN
	1    1950 2400
	1    0    0    -1  
$EndComp
$Comp
L TPS561208 U1
U 1 1 5B26C819
P 1900 1800
F 0 "U1" H 1900 1450 60  0000 C CNN
F 1 "TPS561208" H 1900 2350 60  0000 C CNN
F 2 "TO_SOT_Packages_SMD:SOT-23-6_Handsoldering" H 1900 1800 60  0001 C CNN
F 3 "" H 1900 1800 60  0001 C CNN
	1    1900 1800
	1    0    0    -1  
$EndComp
Wire Wire Line
	1450 1400 1200 1400
Wire Wire Line
	1200 1350 1200 1500
Connection ~ 1200 1400
Wire Wire Line
	1450 1600 1400 1600
Wire Wire Line
	1400 1600 1400 1400
Connection ~ 1400 1400
Wire Wire Line
	1200 1800 1200 2350
Wire Wire Line
	1200 2350 3550 2350
Wire Wire Line
	1950 2350 1950 2400
Wire Wire Line
	2350 1400 2450 1400
Wire Wire Line
	2750 1400 2850 1400
Wire Wire Line
	3150 1400 3650 1400
Wire Wire Line
	3550 1400 3550 1650
Connection ~ 3300 1400
Connection ~ 1950 2350
Wire Wire Line
	2350 2000 2400 2000
Wire Wire Line
	2400 2000 2400 2350
Connection ~ 2400 2350
Wire Wire Line
	2350 1600 2800 1600
Wire Wire Line
	2800 1600 2800 1400
Connection ~ 2800 1400
Wire Wire Line
	3300 1850 2350 1850
Wire Wire Line
	3300 1400 3300 1450
Wire Wire Line
	3300 1750 3300 1900
Connection ~ 3300 1850
Wire Wire Line
	3300 2350 3300 2200
Wire Wire Line
	3550 2350 3550 1950
Connection ~ 3300 2350
Wire Wire Line
	3650 1400 3650 1350
Connection ~ 3550 1400
$Comp
L +5V #PWR7
U 1 1 5B26D61A
P 3650 1350
F 0 "#PWR7" H 3650 1200 50  0001 C CNN
F 1 "+5V" H 3650 1490 50  0000 C CNN
F 2 "" H 3650 1350 50  0001 C CNN
F 3 "" H 3650 1350 50  0001 C CNN
	1    3650 1350
	1    0    0    -1  
$EndComp
Wire Wire Line
	3450 3750 3600 3750
Wire Wire Line
	1450 3650 1450 3750
Wire Wire Line
	1450 3750 1500 3750
Wire Wire Line
	1800 3750 1900 3750
$Comp
L Conn_01x02 J4
U 1 1 5B2700E5
P 5300 2450
F 0 "J4" H 5300 2550 50  0000 C CNN
F 1 "C_PWR" H 5300 2250 50  0000 C CNN
F 2 "jst:CONNECTOR_JST_SH_2" H 5300 2450 50  0001 C CNN
F 3 "" H 5300 2450 50  0001 C CNN
	1    5300 2450
	1    0    0    1   
$EndComp
$Comp
L GND #PWR12
U 1 1 5B27017B
P 5050 2500
F 0 "#PWR12" H 5050 2250 50  0001 C CNN
F 1 "GND" H 5050 2350 50  0000 C CNN
F 2 "" H 5050 2500 50  0001 C CNN
F 3 "" H 5050 2500 50  0001 C CNN
	1    5050 2500
	1    0    0    -1  
$EndComp
Wire Wire Line
	5100 2450 5050 2450
Wire Wire Line
	5050 2450 5050 2500
Wire Wire Line
	5050 2300 5050 2350
Wire Wire Line
	5050 2350 5100 2350
$Comp
L Conn_01x05 J3
U 1 1 5B27081A
P 5300 1450
F 0 "J3" H 5300 1750 50  0000 C CNN
F 1 "Conn_01x05" H 5300 1150 50  0000 C CNN
F 2 "jst:CONNECTOR_JST_SH_5" H 5300 1450 50  0001 C CNN
F 3 "" H 5300 1450 50  0001 C CNN
	1    5300 1450
	1    0    0    1   
$EndComp
Wire Wire Line
	4900 1250 5100 1250
Wire Wire Line
	5100 1350 4900 1350
Text Notes 4450 1250 0    51   ~ 0
Buzzer
Text Notes 4450 1400 0    51   ~ 0
RX
NoConn ~ 5100 1450
Wire Wire Line
	4900 1550 5100 1550
Text Notes 4250 1550 0    51   ~ 0
LED Confirm
Wire Wire Line
	4900 1650 5100 1650
Text Notes 4300 1700 0    51   ~ 0
LED Reject
$Comp
L LoLinESP8266 U2
U 1 1 5B27191B
P 7300 2050
F 0 "U2" H 7300 1150 51  0000 C CNN
F 1 "LoLinESP8266" H 7300 2750 51  0000 C CNN
F 2 "esp-control:LOLIN_NODEMCU" H 7300 2050 51  0001 C CNN
F 3 "" H 7300 2050 51  0001 C CNN
	1    7300 2050
	1    0    0    -1  
$EndComp
$Comp
L +5V #PWR15
U 1 1 5B2719EB
P 6700 1650
F 0 "#PWR15" H 6700 1500 50  0001 C CNN
F 1 "+5V" H 6700 1790 50  0000 C CNN
F 2 "" H 6700 1650 50  0001 C CNN
F 3 "" H 6700 1650 50  0001 C CNN
	1    6700 1650
	0    -1   -1   0   
$EndComp
Wire Wire Line
	6700 1650 6750 1650
Text GLabel 8050 2150 2    55   Output ~ 0
D5
Wire Wire Line
	7800 2150 8050 2150
Text GLabel 8050 1550 2    60   Input ~ 0
D1
Text GLabel 8050 1750 2    60   Input ~ 0
D3
Text GLabel 8250 1850 2    60   Input ~ 0
D4
Text GLabel 8050 2350 2    55   Output ~ 0
D7
Text GLabel 8250 2250 2    55   Output ~ 0
D6
Text GLabel 8250 1650 2    55   Output ~ 0
D2
Wire Wire Line
	8050 1550 7800 1550
Wire Wire Line
	8050 1750 7800 1750
Wire Wire Line
	8250 1650 7800 1650
Wire Wire Line
	8250 1850 7800 1850
Wire Wire Line
	8250 2250 7800 2250
Wire Wire Line
	8050 2350 7800 2350
$Comp
L GND #PWR14
U 1 1 5B272270
P 6500 1550
F 0 "#PWR14" H 6500 1300 50  0001 C CNN
F 1 "GND" H 6500 1400 50  0000 C CNN
F 2 "" H 6500 1550 50  0001 C CNN
F 3 "" H 6500 1550 50  0001 C CNN
	1    6500 1550
	0    1    1    0   
$EndComp
Wire Wire Line
	6500 1550 6750 1550
$Comp
L GND #PWR16
U 1 1 5B272352
P 6700 2350
F 0 "#PWR16" H 6700 2100 50  0001 C CNN
F 1 "GND" H 6700 2200 50  0000 C CNN
F 2 "" H 6700 2350 50  0001 C CNN
F 3 "" H 6700 2350 50  0001 C CNN
	1    6700 2350
	0    1    1    0   
$EndComp
Wire Wire Line
	6700 2350 6750 2350
$Comp
L GND #PWR17
U 1 1 5B27240F
P 6700 2750
F 0 "#PWR17" H 6700 2500 50  0001 C CNN
F 1 "GND" H 6700 2600 50  0000 C CNN
F 2 "" H 6700 2750 50  0001 C CNN
F 3 "" H 6700 2750 50  0001 C CNN
	1    6700 2750
	0    1    1    0   
$EndComp
Wire Wire Line
	6700 2750 6750 2750
$Comp
L GND #PWR19
U 1 1 5B2724EA
P 7900 2750
F 0 "#PWR19" H 7900 2500 50  0001 C CNN
F 1 "GND" H 7900 2600 50  0000 C CNN
F 2 "" H 7900 2750 50  0001 C CNN
F 3 "" H 7900 2750 50  0001 C CNN
	1    7900 2750
	0    -1   -1   0   
$EndComp
Wire Wire Line
	7900 2750 7800 2750
$Comp
L GND #PWR18
U 1 1 5B272583
P 7850 2050
F 0 "#PWR18" H 7850 1800 50  0001 C CNN
F 1 "GND" H 7850 1900 50  0000 C CNN
F 2 "" H 7850 2050 50  0001 C CNN
F 3 "" H 7850 2050 50  0001 C CNN
	1    7850 2050
	0    -1   -1   0   
$EndComp
Wire Wire Line
	7850 2050 7800 2050
$Comp
L +3.3V #PWR13
U 1 1 5B2726D2
P 6350 2450
F 0 "#PWR13" H 6350 2300 50  0001 C CNN
F 1 "+3.3V" H 6350 2590 50  0000 C CNN
F 2 "" H 6350 2450 50  0001 C CNN
F 3 "" H 6350 2450 50  0001 C CNN
	1    6350 2450
	1    0    0    -1  
$EndComp
Wire Wire Line
	6750 2450 6350 2450
$Comp
L +3.3V #PWR20
U 1 1 5B27289D
P 8250 2850
F 0 "#PWR20" H 8250 2700 50  0001 C CNN
F 1 "+3.3V" H 8250 2990 50  0000 C CNN
F 2 "" H 8250 2850 50  0001 C CNN
F 3 "" H 8250 2850 50  0001 C CNN
	1    8250 2850
	1    0    0    -1  
$EndComp
Wire Wire Line
	8250 2850 7800 2850
$Comp
L +3.3V #PWR21
U 1 1 5B272939
P 8600 1950
F 0 "#PWR21" H 8600 1800 50  0001 C CNN
F 1 "+3.3V" H 8600 2090 50  0000 C CNN
F 2 "" H 8600 1950 50  0001 C CNN
F 3 "" H 8600 1950 50  0001 C CNN
	1    8600 1950
	1    0    0    -1  
$EndComp
Wire Wire Line
	8600 1950 7800 1950
$Comp
L Barrel_Jack J5
U 1 1 5B26EC40
P 1400 5300
F 0 "J5" H 1400 5510 50  0000 C CNN
F 1 "Barrel_Jack" H 1400 5125 50  0000 C CNN
F 2 "Connectors:BARREL_JACK" H 1450 5260 50  0001 C CNN
F 3 "" H 1450 5260 50  0001 C CNN
	1    1400 5300
	1    0    0    -1  
$EndComp
$Comp
L GND #PWR5
U 1 1 5B26F00B
P 1800 5400
F 0 "#PWR5" H 1800 5150 50  0001 C CNN
F 1 "GND" H 1800 5250 50  0000 C CNN
F 2 "" H 1800 5400 50  0001 C CNN
F 3 "" H 1800 5400 50  0001 C CNN
	1    1800 5400
	1    0    0    -1  
$EndComp
Wire Wire Line
	1700 5400 1800 5400
Wire Wire Line
	1700 5300 1750 5300
Wire Wire Line
	1750 5300 1750 5400
Connection ~ 1750 5400
$Comp
L +12V #PWR4
U 1 1 5B26F5F6
P 1800 5150
F 0 "#PWR4" H 1800 5000 50  0001 C CNN
F 1 "+12V" H 1800 5290 50  0000 C CNN
F 2 "" H 1800 5150 50  0001 C CNN
F 3 "" H 1800 5150 50  0001 C CNN
	1    1800 5150
	1    0    0    -1  
$EndComp
Wire Wire Line
	1800 5150 1800 5200
Wire Wire Line
	1800 5200 1700 5200
$EndSCHEMATC
