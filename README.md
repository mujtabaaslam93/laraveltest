# Laravel Folowup Task
Create a webpage with a form that has the following text input fields: Product name, Quantity in stock, Price per item.

- The submitted data of the form should be saved in an XML / JSON file with valid XML / JSON syntax.
- Underneath of the form, the web page should display all of the data which has been submitted in rows ordered by date time submitted, the order of the data columns should be: Product name, Quantity in stock, Price per item, Datetime submitted, Total value number.
- The "Total value number" should be calculated as (Quantity in stock * Price per item).
- The last row should show a sum total of all of the Total Value numbers.
- For extra credit, include functionality to edit each line.
## Git URL
https://github.com/mujtabaaslam93/laraveltest
## Setup

Follow these steps to set up the project:

### 1. Clone the Repository

Clone the repository using Git:

```bash
git clone https://github.com/mujtabaaslam93/laraveltest.git
```
Install Dependencies
```bash
composer install
```
Generate Application Key
```bash
php artisan key:generate
```
Run Migrations
```bash
php artisan migrate
```
Serve the Application
```bash
php artisan serve
```