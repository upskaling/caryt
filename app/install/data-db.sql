-- Adminer 4.7.7 SQLite 3 dump

CREATE TABLE "admin_category" (
  "name" text NOT NULL,
  "category" integer NOT NULL,
  PRIMARY KEY ("category")
);

CREATE UNIQUE INDEX "admin_category_name_category" ON "admin_category" ("name", "category");


CREATE TABLE "admin_entry" (
  "url" text NOT NULL,
  "title" text NOT NULL,
  "uploader_url" text NULL,
  "thumbnail" text NULL,
  "description" text NULL,
  "categories" text NULL,
  "get_date" integer NULL,
  "update" integer NULL,
  "is_read" integer NULL,
  "pass" integer NULL
);

CREATE UNIQUE INDEX "admin_waiting_list_url" ON "admin_entry" ("url");


CREATE TABLE "admin_feed" (
  "xmlurl" text NOT NULL,
  "siteurl" text NULL,
  "title" text NOT NULL,
  "update_interval" integer NOT NULL,
  "update" integer NOT NULL,
  "mute" integer NULL,
  "category" integer NOT NULL,
  "status" text NULL,
  FOREIGN KEY ("category") REFERENCES "admin_category" ("category"),
  PRIMARY KEY ("xmlurl")
);

CREATE UNIQUE INDEX "admin_feed_xmlurl" ON "admin_feed" ("xmlurl");


-- 
