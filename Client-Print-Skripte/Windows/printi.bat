@echo off
:: Batch-Skript mit dem man über freigegebene Drucker ansprechen kann. Der SMB-Drucker heißt "Receipt Printer" und die Datei ist die printPOS.txt. Anleitung zur Freigabe & Treibereinstellungen unter "https://mike42.me/blog/2015-04-getting-a-usb-receipt-printer-working-on-windows"

print /D:"\\%COMPUTERNAME%\Receipt Printer" C:\POS-Daten\printPOS.txt
