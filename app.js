const express = require ("express");
const app = express();
require("dotenv").config();
const db = require("./server/db.js");

db.connect((err) => {
    if (err) console.error("Database connection failed:", err);
    else console.log("Connected to MySQL database.");
  });

app.get('/', (req, res) => {
    res.send('Hello Maja!');
});

app.listen(3306, () => {
    console.log('Server started on port 3306');
});