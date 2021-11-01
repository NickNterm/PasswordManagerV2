import React from "react";
import { SafeAreaView, TextInput, StyleSheet, Text } from "react-native";
import AsyncStorage from "@react-native-async-storage/async-storage";
import colors from "../style/colors";
import ButtonFilled from "../widgets/buttonFilled";
import { databaseLogin } from "../database/database";

// Login Screen Function
function loginScreen({ navigation }) {
  // Initialize the React native useState Hooks
  const [username, onUsernameChange] = React.useState("");
  const [password, onPasswordChange] = React.useState("");
  const [error, setError] = React.useState("");
  const [loading, setLoading] = React.useState(false);

  // This function stores the Token to the phone storage
  const loginCallback = async (value) => {
    try {
      await AsyncStorage.setItem("token", value);
    } catch (e) {
      setError(e);
    }
    // go to the next main page the token in saved
    setLoading(false);
    navigation.replace("Home");
  };

  // Show the error and stop showing the loading
  function showError(error) {
    setError(error);
    setLoading(false);
  }

  // This function is done in the login Button Press
  function login() {
    // We give username and password to log the user in
    // In case there is something wrong with the credentials
    // we use the setError to display it
    // If the user logs in successfully then the loginCallback is called.
    setLoading(true);
    databaseLogin(username, password, loginCallback, showError);
  }

  // UI
  return (
    <SafeAreaView
      style={{ flex: 1, alignItems: "center", backgroundColor: "#fff" }}
    >
      <TextInput
        style={[styles.input, { marginTop: 10 }]}
        onChangeText={onUsernameChange}
        placeholder={"Username"}
        value={username}
      />
      <TextInput
        style={styles.input}
        onChangeText={onPasswordChange}
        secureTextEntry={true}
        placeholder={"Password"}
        value={password}
      />
      {error != "" ? (
        <Text
          style={{
            width: "90%",
            textAlign: "center",
            textAlignVertical: "center",
            height: 40,
            color: "#fff",
            backgroundColor: colors.error_red,
            borderRadius: 5,
            marginTop: 5,
          }}
        >
          {error}
        </Text>
      ) : null}
      <ButtonFilled
        text={"Sign In"}
        onClick={() => login()}
        style={{ marginTop: 10 }}
        loading={loading}
      />
    </SafeAreaView>
  );
}

// Styles
const styles = StyleSheet.create({
  input: {
    backgroundColor: colors.grey_input,
    borderRadius: 5,
    padding: 10,
    height: 40,
    width: "90%",
    marginTop: 5,
  },
});
export default loginScreen;
