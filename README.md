<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Thought process

## Setup
	Install php
	Install composer
	Install laravel
	Install posgresql
	Create new table + user for project
	Create .env file with database values

## Steps
### Issues
##### Issue: Database migration
    "relation rooms does not exist"--> migrations are done in alphabetical order
        ==> rename creat_rooms_table.php so migration call is done before create_bookings_table.php
    or
        ==> execute migrate commands in order 

##### Issue: View path not found
    create directorires in storage/framework
##### Issue: No application encryption key has been specified 
    set up APP_KEY variable in .env
##### Implementing Solution
    Search up: sql overlapping dates
    Translate raw query to laravel query builder
    for every booking in $bookings
        Add missing values
        Use query to check for overlappign bookings if so --> add to conflict list
    Return conflict list if not empty, early return, return negative response
    If conflict list empty, add newbookings to db, return positive response
#### Wonder if there are optimisations
    For current naive solution, if n is the amount of bookings in $bookings, 
    then n is the amount of database queries needed to see all potential conflicts
    Potential solutions:
        1) Go through array and early return if there's 1 conflict
            Pros: 
                less queries needed
            Cons:
                If all potential conflicts are needed for further usage, then this solution won't work
        2) Fetch start_time and end_time 
            Pros: 
                1 query needed
            Cons: 
                If all potential conflicts are needed for further usage, then this solution won't work
#### Implement solution 2
    Search up if there's a way to dynamically chain query clauses together
    Define query builder variable
    For each booking in $bookings
        add previously defined where query to query builder variable
    get query builder result
    Check result 
        if query not empty, early return, return negative response

        if query empty, add newbookings to db, return positive response
