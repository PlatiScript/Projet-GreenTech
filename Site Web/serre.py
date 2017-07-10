import serial
import sys

ser = serial.Serial("/dev/ttyACM0", 9600)

print "test";

from serial import *

ser.write(sys.argv[1]);
ser.write(sys.argv[2]);
