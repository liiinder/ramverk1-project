--
-- Table User
--
DROP TABLE IF EXISTS User;
CREATE TABLE User (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "username" TEXT UNIQUE NOT NULL,
    "password" TEXT,
    "email" TEXT,
    "created" TIMESTAMP,
    "updated" DATETIME,
    "deleted" DATETIME,
    "active" DATETIME
);

--
-- Table Post
--
DROP TABLE IF EXISTS Post;
CREATE TABLE Post (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "userId" INTEGER NOT NULL,
    "text" TEXT,
    "title" TEXT,
    "created" TIMESTAMP,
    "updated" DATETIME,
    "deleted" DATETIME,
    "active" DATETIME
);

--
-- Table Comment
--
DROP TABLE IF EXISTS Comment;
CREATE TABLE Comment (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "postId" INTEGER NOT NULL,
    "commentId" INTEGER,
    "userId" INTEGER,
    "text" TEXT,
    "created" TIMESTAMP,
    "updated" DATETIME,
    "deleted" DATETIME,
    "active" DATETIME
);

--
-- Table Tags
--
DROP TABLE IF EXISTS Tags;
CREATE TABLE Tags (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "tag" TEXT UNIQUE NOT NULL
);

--
-- TABLE TagsPost
--
DROP TABLE IF EXISTS TagsPost;
CREATE TABLE TagsPost (
    "tagId" INTEGER,
    "postId" INTEGER,
    PRIMARY KEY ("tagId", "postId")
);