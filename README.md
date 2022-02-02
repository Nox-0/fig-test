#Dan's awesome coding thing

To run the app, first run `npm install` to install all npm packages, run `php artisan migrate` to run the database
migrations, then run `php artisan serve` to start the server and in another terminal window run `npm run dev` to build
the frontend.

To run the tests you need to run `php artisan test`. Or don't! Your life, I'm not one to dictate how you should live it

I think that'd work? You may need to run some sort of mysql stuff to get the database, I've got no clue how that would
work on someone else's computer. It works on mine! :)

Also it uses php 7.3.29-to-be-removed-in-future-macOS

##Apply
The "Apply" button will show you how much your "application" of your fertiliser would cost, given the current stock.
We use a first in first out system to determine the stock used.
####NOTE - the apply button DOES NOT create an application, just shows you how much the application would cost.

I decided to not make the apply button save an application transaction due to easier testing. It would be a simple
function call to set it up, but I didn't think it would be a good idea to then have to add purchases each time you ran
out of the stock.

##Create transaction
The "Create transaction" button creates a transaction. In the first input field, put in the quantity of inventory you 
want "transacted" (is that even a word? lol) and the second input field is for the price of each unit. No need to fill
it out for "Application" transactions. Just make sure that the quantity is positive for purchases and negative for
applications.


###PS
There are a bunch of random classes, files and stuff that were automatically generated when I created the project.
Feel free to ignore them
