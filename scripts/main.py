import sys
from reportlab.pdfgen import canvas
from openpyxl import load_workbook
import argparse
import json

# Create an argument parser
parser = argparse.ArgumentParser(description='Process transaction data')
# Add arguments to retrieve the necessary data
parser.add_argument('--transaction_id', type=int, help='Transaction ID')
parser.add_argument('--client_name', type=str, help='Client name')

# Parse the command-line arguments
args = parser.parse_args()

# Retrieve the data from the parsed arguments
transaction_id = args.transaction_id
client_name = args.client_name


def generate_pdf_bill(transaction_id, client_name, output_path):
    transaction_id = transaction_id
    client_name = client_name

    output_path = f"C:/Users/lenovo/Documents/{transaction_id}.pdf"

    c = canvas.Canvas(output_path)

    c.setFont("Helvetica-Bold", 16)
    c.drawString(100, 700, "Invoice for Transaction ID: {}".format(transaction_id))

    c.setFont("Helvetica", 12)
    c.drawString(100, 650, "Client Name: {}".format(client_name))

    c.save()


# Use the retrieved data to perform further processing, such as generating the PDF bill and updating the Excel file

file = open('demo.txt', 'a')
file.write(client_name)
file.close()

# Generate the PDF bill
pdf_output_path = "C:/Users/lenovo/Documents"
generate_pdf_bill(transaction_id, client_name, pdf_output_path)

# Update the Excel file
# excel_file_path = "/path/to/your/excel/orders.xlsx"
# update_excel_file(transaction_id, excel_file_path)
