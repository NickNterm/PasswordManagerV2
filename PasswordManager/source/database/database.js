// Site that the api key is hosted
var site = "http://192.168.50.9/";

// Fetches data from the api
// It gives the username and the password in the api and
// if the credentials are correct it calls the callback with the token as parameter
// else it calls the error function with the error message as parameter
export function databaseLogin(username, password, callback, error) {
  fetch(site + "api/login?username=" + username + "&password=" + password)
    // Get the whole response and pass only the status and the json
    .then((response) => {
      const statusCode = response.status;
      const data = response.json();
      return Promise.all([statusCode, data]);
    })
    // Check the status code and call the functions accordingly
    .then(([statusCode, data]) => {
      if (statusCode == 200) {
        callback(data.token);
      } else {
        error(data.error);
      }
    })
    .catch((error) => {
      console.log(error);
    });
}

// It gives the username and the password of a new user
// Api creates the user and returns the token if there is no user with the same name
// Else it returns the errorMessage
export function databaseSignUp(username, password, callback, error) {
  fetch(site + "api/signup?username=" + username + "&password=" + password, {
    method: "POST",
  })
    // Get the whole response and pass only the status and the json
    .then((response) => {
      const statusCode = response.status;
      const data = response.json();
      return Promise.all([statusCode, data]);
    })
    // Check the status code and call the functions accordingly
    .then(([statusCode, data]) => {
      if (statusCode == 200) {
        callback(data.token);
      } else {
        error(data.error);
      }
    })
    .catch((error) => {
      console.log(error);
    });
}

export function databaseGetPostsFromToken(token, callback) {
  fetch(site + "/api/post?token=" + token)
    // Get the whole response and pass only the status and the json
    .then((response) => {
      const statusCode = response.status;
      const data = response.json();
      return Promise.all([statusCode, data]);
    })
    // Check the status code and call the functions accordingly
    .then(([statusCode, data]) => {
      if (statusCode == 200) {
        callback(data.list);
      } else {
        console.log(data.error);
      }
    });
}
