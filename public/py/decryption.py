#!/usr/bin/env python
import sys
import json

# Collect string passed into the program
string = sys.argv[1:]
encrypted,d,n = string

def dencrypt(char):
	return (char ** int(d)) % int(n)

def parse(encrypted):
	chunks = 20
	ciphered = [encrypted[i:i+chunks] for i in range(0, len(encrypted), chunks)]
	ciphered = [chr(dencrypt(int(text, 2))) for text in ciphered]
	ciphered = ''.join(ciphered)
	return ciphered

# Return result
print(json.dumps({
	'text' : parse(encrypted)
}))