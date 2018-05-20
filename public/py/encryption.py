#!/usr/bin/env python
import sys
import json
import random
from fractions import gcd

# Collect string passed into the program
string = sys.argv[1:]
string = ' '.join(string)

# Get Prime numbers between 0 and n
def getPrimes(n):
    numbers = set(range(n, 1, -1))
    primes = []
    while numbers:
        p = numbers.pop()
        primes.append(p)
        numbers.difference_update(set(range(p*2, n+1, p)))
    return primes

# Get Prime numbers between 0 and n
def getCoprimes(numbers, l):
	return [number for number in numbers if gcd(number, l) == 1]

# Generate the public key
def generateE():
	return random.choice(getCoprimes(list(range(2,l)), l))

# Generate teh private key
def generateD():
	return random.choice([number for number in range(1,10000) if number * e % l == 1])

# Encrypt character passed
def encrypt(char):
	return (char ** e) %  n

# define global variables
encrypted = ''
primes = getPrimes(100);
p,q = random.sample(primes,2)
n = p*q
l = ( q - 1 ) * ( p - 1)
e = generateE()

# Encrypt all characters of the string
for x in string:
	x = ord(x)
	x = encrypt(x)
	x = bin(x)[2:].zfill(20)
	encrypted+=x

# Return result
print(json.dumps({
	'd' : generateD(),
	'n' : n,
	'e' : e,
	'encrypted' : encrypted
}))