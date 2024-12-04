# Event Sourced Domain Models with Ecotone in PHP

# Running
You can run the example from docker-compose or from your local machine.

```bash
docker-compose up -d
ocker exec -it es-domain php run_finance_example.php
```

### Running run_example.php

Run example is production ready code, which is using Ecotone to run the application.  
It will take care of creating Event Streams in database, therefore you can connect to the db to see how events event streams are stored.

You may also run tests, which works on in memory implementations using Ecotone support for testing.

# Account Domain

This domain contains of User which can be registered with given name.
- We can change user's name.

# Finance Domain
 
Within this domain we track user's wallet balance.  

- User can add money to his wallet, by doing deposit.
- Merchant transactions are transactions done in shops.  
When transaction in the shop is started, it can be completed or canceled.  
When transaction is completed, money is subtracted from user's wallet.
- For this domain we don't care that user may exceed his wallet balance (we could make him pay fee for that for example).

# Smart House Domain

This domain allow for user to control his house based on events happening in the house.  
It does track all the Events happening within registered house.