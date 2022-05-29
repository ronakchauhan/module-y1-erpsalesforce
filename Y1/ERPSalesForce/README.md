# ERP SalesForce Module

Requirements
On every new order, the Message Queue should receive a new message containing the order ID, the email address of the user who placed the order, and the amount of items in the cart, and the same information should be logged using the default logger
Orders information in the Message Queue should be periodically processed and transmitted to the ERP
Orders that are successfully transmitted to the ERP must have their status changed from “new” to “processing”
Every transmission attempt must be logged to a database table. The following information must be recorded: order ID, timestamp, return code from the ERP
The database table containing the transmission logs should never be empty. On table creation, a single line should be created, with order ID 0, current timestamp at creation time, and return code 999
An html page reachable at /erp_sync/items/status should show the last 10 transmission attempts. This should not be part of the admin panel!
There should be a CLI command that show a list of the last 10 successful transmission attempts, or the last 10 failed transmission attempts, based on an argument passed to it

For the purpose of this challenge, you can assume the following:
the ERP uses a REST API to receive order information from Adobe Commerce
the ERP will respond with a 200 if the order is successfully received, and 400/500 in case of failures
you can fake the request/response flow with ERP, as long as we can verify all the cases


# Commands

Command to Publish Message Queue
 - sync:order:publish

Command to Start Consumer :
 - queue:consumers:start sync:order:publish

Command to fetch Transmissions
 - sync:order:transmission
