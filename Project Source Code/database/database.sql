--
-- File generated with SQLiteStudio v3.3.3 on Wed Apr 6 22:17:16 2022
--
-- Text encoding used: System
--
PRAGMA foreign_keys = off;
BEGIN TRANSACTION;

-- Table: Assets
CREATE TABLE Assets (AssetID INTEGER PRIMARY KEY AUTOINCREMENT);

-- Table: TicketPriority
CREATE TABLE TicketPriority (PriorityID INTEGER PRIMARY KEY AUTOINCREMENT, Title VARCHAR, Description VARCHAR);
INSERT INTO TicketPriority (PriorityID, Title, Description) VALUES (1, 'New Unassigned Ticket', '');
INSERT INTO TicketPriority (PriorityID, Title, Description) VALUES (2, 'Priority', NULL);
INSERT INTO TicketPriority (PriorityID, Title, Description) VALUES (3, 'Assigned Ticket', NULL);
INSERT INTO TicketPriority (PriorityID, Title, Description) VALUES (4, 'Waiting on Vendor', NULL);
INSERT INTO TicketPriority (PriorityID, Title, Description) VALUES (5, 'Waiting Approval', NULL);
INSERT INTO TicketPriority (PriorityID, Title, Description) VALUES (6, 'Insurance Assessment Pending', NULL);
INSERT INTO TicketPriority (PriorityID, Title, Description) VALUES (7, 'Approved', NULL);
INSERT INTO TicketPriority (PriorityID, Title, Description) VALUES (9, 'Completed', NULL);
INSERT INTO TicketPriority (PriorityID, Title, Description) VALUES (10, 'Resolved', NULL);

-- Table: Tickets
CREATE TABLE Tickets (TicketID INTEGER PRIMARY KEY AUTOINCREMENT, Priority INTEGER REFERENCES TicketPriority (PriorityID), Status INTEGER REFERENCES TicketStatus (StatusID), Title VARCHAR, UserGroup INTEGER REFERENCES UserGroups (GroupID), UserID INTEGER REFERENCES Users (UserID), Created DATETIME, Comment TEXT, Asset REFERENCES Assets (AssetID));

-- Table: TicketStatus
CREATE TABLE TicketStatus (StatusID INTEGER PRIMARY KEY AUTOINCREMENT, Title VARCHAR, Description VARCHAR);
INSERT INTO TicketStatus (StatusID, Title, Description) VALUES (1, 'Critical - Unit out of Service', 'Unit out of Service');
INSERT INTO TicketStatus (StatusID, Title, Description) VALUES (2, 'Under investigation due to injury', NULL);
INSERT INTO TicketStatus (StatusID, Title, Description) VALUES (3, 'Defect Notice - VOR', NULL);
INSERT INTO TicketStatus (StatusID, Title, Description) VALUES (4, 'Defect Notice - Self Clearing', NULL);
INSERT INTO TicketStatus (StatusID, Title, Description) VALUES (5, 'Cosmetic Inspection Required', NULL);
INSERT INTO TicketStatus (StatusID, Title, Description) VALUES (6, 'High - Down time to be scheduled', NULL);
INSERT INTO TicketStatus (StatusID, Title, Description) VALUES (7, 'Medium - Next scheduled maintance', NULL);

-- Table: UserGroupMapping
CREATE TABLE UserGroupMapping (UserID INTEGER REFERENCES users (UserID), GroupID INTEGER REFERENCES UserGroups (GroupID));

-- Table: UserGroups
CREATE TABLE UserGroups (GroupID INTEGER PRIMARY KEY AUTOINCREMENT, GroupName VARCHAR, GroupDescription VARCHAR, Location VARCHAR, Manager INTEGER REFERENCES Users (UserID), Supervisor INTEGER REFERENCES Users (UserID));

-- Table: Users
CREATE TABLE Users (UserID INTEGER PRIMARY KEY AUTOINCREMENT, Position VARCHAR, Username VARCHAR UNIQUE, Password VARCHAR, Email VARCHAR, FirstName VARCHAR, LastName VARCHAR, LicenseNumber INTEGER, LicenseState VARCHAR, LicenseType VARCHAR, AdminAccess BOOLEAN, StartDate DATE, FinishDate DATE);

COMMIT TRANSACTION;
PRAGMA foreign_keys = on;
