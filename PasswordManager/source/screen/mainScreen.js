import React, { useEffect } from "react";
import { View, Text, FlatList, TouchableWithoutFeedback } from "react-native";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { databaseGetPostsFromToken } from "../database/database";
import { Ionicons } from "@expo/vector-icons";
import { NavigationContainer } from "@react-navigation/native";
import colors from "../style/colors";
function mainScreen({ navigation }) {
  const [postList, setPostList] = React.useState([]);

  var token = "";

  const getToken = async () => {
    try {
      const value = await AsyncStorage.getItem("token");
      if (value !== null) {
        token = value;
        databaseGetPostsFromToken(token, getPostsCallback);
      }
    } catch (error) {
      console.log(error);
    }
  };
  useEffect(() => {
    getToken();
    navigation.setOptions({
      headerRight: (props) => (
        <Ionicons
          name="add"
          size={30}
          color="white"
          style={{ marginEnd: 15 }}
          onPress={() => {
            navigation.navigate("NewPost");
          }}
        />
      ),
    });
  }, []);
  function getPostsCallback(list) {
    if (list.length == 0) {
      console.log("empty");
    } else {
      setPostList(list);
    }
  }
  return (
    <View>
      <FlatList
        style={{ paddingTop: 10 }}
        extraData={postList}
        data={postList}
        keyExtractor={(item) => item.id.toString()}
        renderItem={({ item }) => (
          <View
            style={{
              alignSelf: "center",
              width: "95%",
              marginBottom: 10,
              borderRadius: 5,
              borderWidth: 1.5,
              borderColor: item.color ? item.color : colors.primary_color,
            }}
          >
            <TouchableWithoutFeedback>
              <View>
                <Text
                  style={{
                    fontSize: 16,
                    color: "#fff",
                    padding: 15,
                    fontWeight: "bold",
                    textAlign: "center",
                    backgroundColor: item.color
                      ? item.color
                      : colors.primary_color,
                  }}
                >
                  {item.platform}
                </Text>
                <Text
                  style={{
                    fontSize: 16,
                    color: "#000",
                    textAlign: "center",
                    padding: 15,
                  }}
                >
                  {item.more_info}
                </Text>
              </View>
            </TouchableWithoutFeedback>
          </View>
        )}
      />
    </View>
  );
}

export default mainScreen;
