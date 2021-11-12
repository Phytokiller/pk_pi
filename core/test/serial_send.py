import serial
import time

ser = serial.Serial(port='/dev/ttyAMA3', baudrate=38400)
try :
	ser.open()
except :
	print("already open")

def send(message) :
	byte_to_send = bytes(message, 'utf-8')
	ser.write(byte_to_send)

while True :
	# Read
	ligne = ser.read_line()
    print ligne
    # Write
#	send('%test1\n')
#	time.sleep(1)
#	send('%test2\n')
#	time.sleep(2)

