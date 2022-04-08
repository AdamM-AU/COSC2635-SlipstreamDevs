--
-- File generated with SQLiteStudio v3.3.3 on Fri Apr 8 21:01:58 2022
--
-- Text encoding used: System
--
PRAGMA foreign_keys = off;
BEGIN TRANSACTION;

-- Table: Assets
CREATE TABLE Assets (AssetID INTEGER PRIMARY KEY AUTOINCREMENT, AssetType VARCHAR, AquistionDate DATE, AssetESL VARCHAR, AquisitionExpense NUMERIC, AssetLocation VARCHAR, MaintenanceInterval VARCHAR, RegistationRequired BOOLEAN, AuthorityInspectionReq TEXT, LicenseReq TEXT);

-- Table: FileUploads
CREATE TABLE FileUploads (UploadID INTEGER PRIMARY KEY AUTOINCREMENT, TicketID INTEGER REFERENCES Tickets (TicketID), CommentID INTEGER REFERENCES TicketComments (CommentID), DateCreated DATETIME, OriginalFileName VARCHAR, MD5FileName VARCHAR, UserID REFERENCES Users (UserID));

-- Table: ScheduledTasks
CREATE TABLE ScheduledTasks (TaskID INTEGER PRIMARY KEY AUTOINCREMENT, Name VARCHAR, Description VARCHAR, TaskCreatorUserID INTEGER REFERENCES Users (UserID), TicketTitle VARCHAR, TicketContent VARCHAR, Status INTEGER REFERENCES TicketStatus (StatusID), Priority INTEGER REFERENCES TicketPriority (PriorityID), AssignedGroup INTEGER REFERENCES UserGroups (GroupID), AssignedUser INTEGER REFERENCES Users (UserID), AssignedAsset INTEGER REFERENCES Assets (AssetID), Interval VARCHAR);

-- Table: TicketComments
CREATE TABLE TicketComments (CommentID INTEGER PRIMARY KEY AUTOINCREMENT, TicketID INTEGER REFERENCES Tickets (TicketID) NOT NULL, DateCreated DATETIME, UserID INTEGER REFERENCES Users (UserID), GroupID INTEGER REFERENCES UserGroups (GroupID), Comment TEXT, WasStatusUpdate BOOLEAN);

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
CREATE TABLE Tickets (TicketID INTEGER PRIMARY KEY AUTOINCREMENT, Priority INTEGER REFERENCES TicketPriority (PriorityID), Status INTEGER REFERENCES TicketStatus (StatusID), Title VARCHAR, UserGroup INTEGER REFERENCES UserGroups (GroupID), UserID INTEGER REFERENCES Users (UserID), Created DATETIME, Comment TEXT, Asset INTEGER REFERENCES Assets (AssetID));

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
