--
-- Table User
--
DROP TABLE IF EXISTS User;
CREATE TABLE User (
    "userId" INTEGER PRIMARY KEY NOT NULL,
    "username" TEXT UNIQUE NOT NULL,
    "password" TEXT,
    "email" TEXT
    -- "created" TIMESTAMP,
    -- "updated" DATETIME,
    -- "deleted" DATETIME,
    -- "active" DATETIME
);

--
-- Table Post
--
DROP TABLE IF EXISTS Post;
CREATE TABLE Post (
    "postId" INTEGER PRIMARY KEY NOT NULL,
    "userId" INTEGER NOT NULL,
    "text" TEXT,
    "title" TEXT
    -- "created" TIMESTAMP,
    -- "updated" DATETIME,
    -- "deleted" DATETIME,
    -- "active" DATETIME
);

--
-- Table Comment
--
DROP TABLE IF EXISTS Comment;
CREATE TABLE Comment (
    "commentId" INTEGER PRIMARY KEY NOT NULL,
    "postId" INTEGER NOT NULL,
    "replyId" INTEGER,
    "userId" INTEGER,
    "text" TEXT
    -- "created" TIMESTAMP,
    -- "updated" DATETIME,
    -- "deleted" DATETIME,
    -- "active" DATETIME
);

--
-- Table Tags
--
DROP TABLE IF EXISTS Tags;
CREATE TABLE Tags (
    "tagId" INTEGER PRIMARY KEY NOT NULL,
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