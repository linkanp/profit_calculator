# Profit Calculator
This is a small project to calculate profit according to FIFO sale principal. 
According to the tax laws and accounting standards when trading in tangible products their accounting
must be done using the FIFO (first-in, first-out) principle. I.e. the product that was purchased from the
supplier at the earliest date, must be sold before other items that were purchased later.
This becomes important when calculating profits and margins in particular – i.e. how much the sale
price was higher than the purchase price – especially when the inflow and outflow of products to/from
the warehouse is continuous.
Example scenario: Lets say we purchased 10 garden gnomes for €17 each and then sold 6 of them for
€21 each. Then we purchased 10 more gnomes for €20 each and sold 8 more for €23 each.
It means that the first 6 gnomes were bought for €17 and sold for 21€ thus making a profit of €4 per
item or €24 in total. Then, in order to sell the next 8 gnomes we first have to sell 4 gnomes from the first
purchase batch (purchased for €17 each) and then 4 gnomes from the second batch (at €20 each). So
the profit from this second sale is (€23-€17)x4 + (€23-€20)x4 = €6x4 + €3x4 = €36. And thus the total
margin of all sales was €24 + €36 = €60.
The remaining 6 gnomes are not included in the calculations as they have not been sold yet.

## Technical Requirements
1. PHP 7.2.5 or higher and these PHP extensions (which are installed and enabled by default in most PHP 7 installations): Ctype, iconv, JSON, PCRE, Session, SimpleXML, and Tokenizer;
2. Composer, which is used to install PHP packages.
3. Symfony CLI

After Symfony CLI, you can check the technical requirements by running the following commands and then take action to fullfill all requirements. 

`symfony check:requirements`

## Installation
After cloning the repo in your local, Run the composer install and yarn install command from the project root directory.

`composer install`

`yarn install`

## Database Setup
Modify the DB connection information as per your local DB server. Update the [db_username] and [password] in the line below in .env file.

`DATABASE_URL=mysql://[db_username]:[password]@127.0.0.1:3306/profit_calculator?serverVersion=5.7`

Then run the following command to create the DB 'profit_calculator' and tables in your local DB server.

`php bin/console doctrine:database:create`

`php bin/console doctrine:migrations:migrate`

## Run the Project

`symfony server:start`

Browse the project from http://127.0.0.1:8000

### Cheers!!

Home page will show the current calculated profit and a form to peform Sale/Buy action. Buy action will store the data into Buy table and Sale action will store the data into Sale table and then re-calculate the profit based on new sale.

## Testing
Run the following command to execute the testing script.

`php bin/phpunit tests/`
