@echo off
:: Batch-Skript mit dem man �ber freigegebene Drucker ansprechen kann. Der SMB-Drucker hei�t "Receipt Printer" und die Datei ist die printPOS.txt. Anleitung zur Freigabe & Treibereinstellungen unter "https://mike42.me/blog/2015-04-getting-a-usb-receipt-printer-working-on-windows"

print /D:"\\%COMPUTERNAME%\Receipt Printer" printPOS.txt


