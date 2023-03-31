package edu.rice.rubis.client;

import com.fasterxml.jackson.databind.ObjectMapper;

import java.io.IOException;
import java.util.Arrays;
import java.util.List;
import java.util.Random;

public class UserGenerator {

    private List<User> users;
    private int usersSize;
    private Random random;

    public UserGenerator(RUBiSProperties properties) {
        try {
            ObjectMapper mapper = new ObjectMapper();
            this.users = Arrays.asList(mapper.readValue(this.getClass().getResourceAsStream("/" + properties.getUsersFile()), User[].class));
            random = new Random();
            usersSize = users.size();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public User getRandomUser() {
        return users.get(random.nextInt(usersSize));
    }
}
