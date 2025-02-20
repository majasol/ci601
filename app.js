const express = require("express");
const app = express();
require("dotenv").config();
const db = require("./server/db.js");
const path = require("path");
const { auth } = require("express-openid-connect");
const port = process.env.PORT;

db.connect((err) => {
    if (err) console.error("Database connection failed:", err);
    else console.log("Connected to MySQL database.");
});

const config = {
    authRequired: false,
    auth0Logout: true,
    secret: process.env.SECRET,
    baseURL: process.env.BASEURL,
    clientID: process.env.CLIENTID,
    issuerBaseURL: process.env.ISSUER,
};

app.set("view engine", "ejs");

app.use(express.urlencoded({ extended: true }));

// auth router attaches /login, /logout, and /callback routes to the baseURL
app.use(auth(config));

// req.isAuthenticated is provided from the auth router
app.get("/", (req, res) => {
  console.log(req.oidc.isAuthenticated());
  const isAuthenticated = req.oidc.isAuthenticated();
  const response = isAuthenticated ? req.oidc.user : "Not logged in";
  res.send(response);
});


// ** Static Pages **
//app.get("/", (req, res) => res.sendFile(path.join(__dirname, "views", "index.html")));

app.listen(port, () => {
    console.log('Server started on port 3306');
});