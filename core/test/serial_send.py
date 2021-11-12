import serial
import time

ser = serial.Serial(port='/dev/ttyAMA3', baudrate=38400)
ser.open()

def send(message) :
	byte_to_send = bytes(message, 'utf-8')
	ser.write(byte_to_send)

while True :
	send('test1')
	time.sleep(1)
	send('test2')
	time.sleep(2)

