TeaMeeting
=========

Project Case Study: Student team project meeting agenda / minutes / actions management
========================================================================================

# Overview

An online, student teamwork project system.

Admin
--
can create new students
can create new projects (must assign 2 students when creating a project - 'project leader' and 'project secretary')
can 'lock' and 'unlock' projects (team members cannot make any changes to a 'locked' project)

Roles
--
Each team has at least 2 members.
Each team has 2 required 'core' roles: 'project leader' and 'project secretary'
At least one member of the team must be 'project leader'
At least one member of the team must be 'project secretary'
The same person cannot have both 'core' roles.
The 'project leader' may create new roles for the project, and assign them to team members (team members may have more than 1 role)

Meetings
--
The team has meetings, and each is documented with a set of 'minutes' and an 'agenda'.
The 'project leader' can schedule a new meeting: to takes place on a date / at a time / in a room.
Team members can see a list of future meetings (and can indicate future meeting likely attendance as 'yes / no / maybe')
Team members can change meeting attendance from 'no' or 'maybe' to 'yes' (they CANNOT change from 'yes' to any other value)
For any future meeting all team members can see a percentage of how many team members have indicated they can attend.

Each meeting has one person who is the 'meeting chair' for that meeting, and one person who is the 'meeting secretary' for that meeting.
The default is that the 'meeting chair' = 'project leader', and 'meeting secretary' = 'project secretary', but the project leader can change these before the meeting takes place.

Meeting agenda
--
Each future meeting has an agenda, whose minimum first 3 items are:
1. Note apologies received from team members unable to be present
2. agree agenda
3. accept minutes of previous meeting (unless meeting is first for this project!)
(so when a new meeting is created, the initial agenda has these 3 items, and these have to be the first 3 items for EVERY meeting)

Agenda items are numbered in sequence (1), (2), (3), (4) etc.

Each agenda item has a 'proposer' - the team member who put forward that item on the agenda.
The 'meeting chair' is the proposer for the standard items (1), (2) and (3)

Meetings are scheduled for a future day/time/room/duration, and a draft agenda is published. A deadline is set before the meeting, which is the 'cut-off' deadline for team members to request additions/changes to the agenda.

Team members may send the team 'meeting chair' request for
1. additional agenda items
2. changes to agenda items
3. postponing of agenda items to a later meeting

The 'meeting chair' can update the draft agenda with new versions. If any items are added, the 'meeting chair' indicates which team member has proposed the item.

Any team member can see the record of requests to the 'meeting chair' for agenda addition/change.
The 'meeting chair' can record, for each addition/change a status of
* pending
* noted (no change to agenda)
* agreed (new item on agenda)
* agreed (rewording/re-ordering of item on agenda)

Once the deadline has passed for agenda changes, the next agenda version published by the meeting chair becomes the final meeting agenda.
Any team member can see the current, and all previous, drafts of the agenda

Meeting minutes
--
After the meeting, the 'meeting secretary' publishes a draft set of minutes, recording:
  - who was present ('for whole meeting' 'arrived late' 'left early' 'arrived late and left early')
  - absent (no apologies presented / apologies received before the meeting / apologies received after the meeting)
  - for EVERY agenda item:
      - any comments / notes (may be blank)
      - any actions (there may be none)
      - POSTONED TO FUTURE MEETING
      - any A.O.B. (Any Other Business) items/actions, AOB-1, AOB-2, AOB-3 etc. (each can have comments-notes and action(s))

Team members who were PRESENT at the meeting, may post comments on the draft minutes.
The 'meeting secretary' can update the draft minutes with new versions.
Any team member can see the current, and all previous, drafts of the meeting minutes.

Note - the minutes will not change to 'agreed final version' until after the next meeting.

Minute actions
--
each item on the meeting minutes may be minuted as having 0 or more ACTIONS
each action is:
- IN PROGRESS (not complete before deadline)
- LATE (not complete, after deadline)
- WORK UNDER REVIEW (submitted as complete by team member, but not yet 'signed off' by 'project leader')
- COMPLETE (with completion date, and number of dates late if completion date after the deadline)
- NO LONGER REQUIRED (and date that 'project leader' changed action to this status)

each ACTION has a:
- description
- named 'implementer' (must be a member of the team)
- deadline (time and date)

team members may submit that they have COMPLETED an action on them + with a comment about the action. The action then goes into the WORK UNDER REVIEW status.

the 'project leader' can review team member's submissions of completed actions. They can change the status of each action
(e.g. back to IN PROGEESS / LATE if the work does not satisfy the 'project leader', or to COMPLETE or NO LONGER REQURIED)
