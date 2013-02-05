Amercia Elections Co.
============

'Amercia Elections', a project which took place for #ElectionClass, is a full-featured touch-based voting platform. The real-time voting system includes a voter registration system, queue management (for voters in line), real-time analytics (powered by WebSockets, using Pusher), and a fully anonomyous -- yet accountable -- voting process. 
The class held a fake election for President of 'Amercia', after a long season of campaigning. Details of the Syracuse University class are available at http://electionclass.com

The touch-screen voting system was built with PHP & javascript. The platform features a front-end voting interface and a back-end administration section.

Here's how the system worked on Election Day:

1) Voters would approach 'registration', where a staff member would enter their student ID number into the system

2) Their student ID number was hashed and stored in the database -- which both prevented users from voting more than once and allowed us to anonymously track data trends.

3) Once the new voter was registered, a notification was sent to Pusher.

4) The 'voting computer' received the notification from Pusher, checked the DB for new voters, stored the voter's ID in session & activated the voting process.

5) The voter tapped "Let's vote" to begin. A notification that the user started voting was sent back to Pusher.

6) After choosing their candidate and answering the exit poll questions, another notification was sent back to Pusher. A message calling the next voter was displayed on a third monitor.

7) A PHP script polled the DB to check for other voters who hadn't yet voted & activated the touch-screen, if any existed.

The system was profiled at http://ischool.syr.edu/newsroom/news.aspx?recid=1373

Stats from our election are available at http://vote.anewamercia.com
  
---------

by Andrew Bauer (@awbauer9) and Chris Becker (@cbeck527)
