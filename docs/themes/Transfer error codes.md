# Transfer Error Codes

The `transferError` query parameter in the $_GET object plays a critical role in handling potential errors that may occur during a transfer operation. Query parameters in this instance form a part of the URL that is returned by the server redirect when an error occurs after form submission.

In this case, the transferError parameter will contain a numeric value that pertains to a specific error condition encountered during the transaction processing. This error code is dynamically included in the URL query string, and can be used by the client-side code to display an appropriate error message to the user.

This means that if you see a URL such as http://www.example.com/Transfer.php?transferError=1, this indicates that a transferError of type 1 has occurred, and the corresponding error message can be displayed to the user. This mechanism allows for immediate, clear user feedback on page load.

### Error definitions:

- `transferError = 1`: This code raises an alert when the bank transfer operation encounters unacceptable inputs - faulty transaction sums, transferring money with the same source and destination accounts or unrecognized accounts. The user is notified with "You must transfer a valid amount between valid accounts. Please try again," urging them to correct their inputs.

- `transferError = 2`: This code is activated when a transaction is initiated from or to a frozen or flagged account. It serves as a fraud prevention measure; the user receives a fraud alert - "The selected account has been flagged due to potential fraudulent activity. Please contact support," ensuring safe transactions.

- `transferError = 3`: When the source account's balance is insufficient for the transaction, this error code halts the execution. The user is informed with "There are insufficient funds in the source account for the transaction. Please check your balance and try again," facilitating smooth transactions without overdraft.

- `transferError` other values: If the transferError query parameter holds a value other than 1, 2, and 3, it usually suggests a MySQL error or an unforeseen error. A generic error message is shown in such cases to indicate that an error has occurred.