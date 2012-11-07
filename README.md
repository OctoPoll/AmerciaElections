Vote-Amercia
============

SMS voting app built for the #ElectionClass primary election.

Uses PHP, Twilio, and a little magic.

--------

Users are verified by "netid", which have been stored in a users table. 
* The first time a user votes, the phone number is tied to their profile
* Once a phone number is set in their profile, all future votes must come from that number

Once authenticated, an anonymous vote for the candidate is recorded in the votes table.
* An entry is also placed in the log table, noting the election the user voted in.
* Users can only vote once in each election
  
---------

by Andrew Bauer (@awbauer9) and Chris Becker (@cbeck527)