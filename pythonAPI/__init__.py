from flask import Flask
from flask_restful import Resource, Api, reqparse
from flask_cors import CORS
import mariadb
import hashlib
import string
import random
import pandas as pd
import ast
import sys
app = Flask(__name__)
# api = Api(app)
CORS(app)
conn = mariadb.connect(
    user="root",
    password="iqsoft",
    host="0.0.0.0",
    port=3306,
    database="PasswordManager"
)

# Get Cursor
cur = conn.cursor()


# Login function for the api that gives the user Token
@app.route("/login", methods=["GET"])
def login():
    # Read the parameters from the url
    parser = reqparse.RequestParser()
    parser.add_argument('username')
    parser.add_argument('password')
    args = parser.parse_args()

    # Check if the any of the parameters are missing
    if args['username'] == None or args['password'] == None:
        return {"error": "Enter your credentials"}, 404

    username = args['username']

    # Get the salt from the database so that we can check the password
    cur.execute(
        "SELECT salt FROM login WHERE username=?", (username,))
    salt = cur.fetchone()

    # Check if the salt in null. If it is then the user does not exist
    if salt == None:
        return {"error": "Cannot find a user with this usename"}, 404

    # If the salt exist then create the password string and encode it
    password = hashlib.sha256(
        (salt[0] + args['password']).encode()).hexdigest()

    # Then check the cobination username and password and get the token for the user
    cur.execute(
        "SELECT token FROM login WHERE username=? AND password=?", (username, password,))
    token = cur.fetchone()

    # Check if the the Token is null. If it is then the password is wrong
    if token == None:
        return {"error": "Wrong password"}, 404

    # Else everything is correct and the user is logged in
    return {"token": token[0]}, 200


@app.route("/signup", methods=["POST"])
def SignUp():
    # Read the parameters from the url
    parser = reqparse.RequestParser()
    parser.add_argument('username')
    parser.add_argument('password')
    args = parser.parse_args()

    username = args["username"]
    password = args["password"]

    # Check for missing parameters
    if args['username'] == None or args['password'] == None:
        return {"error": "Missing parameters"}, 404

    # Checking if the given user exists
    cur.execute("SELECT token FROM login WHERE username=?", (username,))
    token = cur.fetchone()

    # If token is not null that means another user exists with the
    # same name and return error
    if token != None:
        return {"error": "A user with this name already exists"}, 404

    # Check if the username length is appropriate
    if len(username) < 6:
        return {"error": "Username needs to be at least 4 characters long"}, 404

    # Check if the password length is appropriate
    if len(password) < 6:
        return {"error": "Password needs to be at least 6 characters long"}, 404

    # Create random salt for the user
    salt = ''.join(random.choices(
        string.ascii_letters + string.digits, k=10))

    # Create the hashed password
    password = hashlib.sha256((salt + password).encode()).hexdigest()

    # Generate a random token and check if another user has the same token
    token = ""
    while(token == ""):
        createdToken = ''.join(random.choices(
            string.ascii_letters + string.digits, k=12))
        cur.execute("SELECT username FROM login WHERE token=?",
                    (createdToken,))
        pickedUser = cur.fetchone()
        if pickedUser == None:
            token = createdToken
            break

    # Finally insert user in the database and commit the change
    # Else if something is wrong just show an error
    try:
        cur.execute(
            "INSERT INTO login (username, password, token, salt) VALUES (?, ?, ?, ?)", (username, password, token, salt))
        conn.commit()
    except mariadb.Error as e:
        return {"error": "Somethink went wrong. Try again"}, 404
    return {"token": token}


# Login function for the api that gives the user Token
@app.route("/post", methods=["GET"])
def Post():
    # Read the parameters from the url
    parser = reqparse.RequestParser()
    parser.add_argument('token')
    args = parser.parse_args()

    # Check if the any of the parameters are missing
    if args['token'] == None:
        return {"error": "Missing Parameter"}, 404

    token = args['token']

    # Get the salt from the database so that we can check the password
    cur.execute(
        "SELECT platform, username, password, password_salt, hint, more_info, id, color FROM data WHERE token=?", (token,))

    # Initialize and add all the selected data in the list
    postList = []
    for (platform, username, password, password_salt, hint, more_info, id, color) in cur:
        item = {
            "platform": platform,
            "username": username,
            "password": password,
            "password_salt": password_salt,
            "hint": hint,
            "more_info": more_info,
            "id": id,
            "color": color,
        }
        postList.append(item)

    # Else everything is correct and the user is logged in
    return {"list": postList}, 200


if __name__ == '__main__':
    app.run()
