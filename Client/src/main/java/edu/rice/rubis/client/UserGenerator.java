package edu.rice.rubis.client;

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.util.ArrayList;
import java.util.List;
import java.util.Objects;
import java.util.Random;

public class UserGenerator {

    private List<String> userIds;
    private int usersSize;
    private Random random;

    public UserGenerator(RUBiSProperties properties) {
        try {
            BufferedReader reader = new BufferedReader(new InputStreamReader(
                    Objects.requireNonNull(this.getClass().getResourceAsStream("/" + properties.getUsersFile()))));
            userIds = new ArrayList<>();
            String line = reader.readLine();
            while (line != null) {
                if (line.isBlank()) {
                    continue;
                }
                userIds.add(line);
                line = reader.readLine();
            }
            random = new Random();
            usersSize = userIds.size();
            reader.close();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public UserGenerator(List<String> usersIds) {
        this.userIds = usersIds;
        random = new Random();
        usersSize = userIds.size();
    }

    public String getRandomUser() {
        return userIds.get(random.nextInt(usersSize));
    }
}
