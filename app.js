const express = require ("express");
const app = express();

app.get('/', (req, res) => {
    res.send('Hello Maja!');
});

app.listen(3306, () => {
    console.log('Server started on port 3306');
});