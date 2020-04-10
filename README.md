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

## Installation
Run the composer install command from the project root directory.
`composer install`
`yarn install`

## Database Setup
Modify the DB connection information as per your local DB server. Update the [db_username] and [password] in the line below in .env file.
`DATABASE_URL=mysql://[db_username]:[password]@127.0.0.1:3306/profit_calculator?serverVersion=5.7`
Then run the following command,
`php bin/console doctrine:database:create`
`php bin/console doctrine:migrations:migrate`

## Run the Project
`symfony server:start`
Browse the project from http://127.0.0.1:8000

### Cheers!!

