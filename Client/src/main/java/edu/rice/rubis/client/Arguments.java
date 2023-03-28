package edu.rice.rubis.client;

import java.util.ArrayList;
import java.util.List;
import java.util.Objects;
import java.util.stream.Collectors;

public class Arguments {

    private static String USAGE = "ClientEmulator usage:\n" +
            "   -h <string> - host, optional, default in properties\n" +
            "   -p <number> - port, optional, default in properties\n" +
            "   -prop <string> - properties file name (without .properties extension), optional, default = rubis\n" +
            "   -main <boolean> - is main client, optional, default = false\n" +
            "   -backends <string> - list of backend services, e.g. host1:80,host2:80, required for main\n" +
            "   -reportDir <string> - path to report file, required for not main" +
            "   -statsDir <string> - path to stats file, required for not main";

    private static String PRINT_ARGS_FORMAT = "ClientEmulator arguments:\n" +
            "   backend host: %s\n" +
            "   backend port: %d\n" +
            "   properties file: %s\n" +
            "   main: %b\n" +
            "   backends: %s\n" +
            "   reportDir: %s\n" +
            "   statsDir: %s";

    private String host;
    private int port;
    private String properties = "rubis";
    private boolean main = false;
    private String reportDir;
    private String statsDir;
    private List<Backend> backends;

    public Arguments(String[] args) throws IllegalArgumentException {
        for (int i = 0; i < args.length; i = i + 2) {
            String currentArg = args[i];
            try {
                switch (currentArg) {
                    case "-h":
                        host = args[i + 1].trim();
                        break;
                    case "-p":
                        port = Integer.parseInt(args[i + 1].trim());
                        break;
                    case "-prop":
                        properties = args[i + 1].trim();
                        break;
                    case "-main":
                        main = Boolean.parseBoolean(args[i + 1].trim());
                        break;
                    case "-backends":
                        String backendsStr = args[i + 1].trim();
                        String[] splitBackends = backendsStr.split(",");
                        backends = new ArrayList<>();
                        for (String splitBackend : splitBackends) {
                            String[] singleBackedSplit = splitBackend.trim().split(":");
                            backends.add(singleBackedSplit.length > 1
                                    ? new Backend(singleBackedSplit[0], Integer.parseInt(singleBackedSplit[1]))
                                    : new Backend(singleBackedSplit[0])
                            );
                        }
                        break;
                    case "-reportDir":
                        reportDir = args[i + 1].trim();
                        break;
                    case "-statsDir":
                        statsDir = args[i + 1].trim();
                        break;
                    default:
                        throw new IllegalArgumentException("Argument not recognized: " + currentArg);
                }
                printArgs();
            } catch (Exception e) {
                printUsage();
                throw new IllegalArgumentException(e.getMessage());
            }
        }
    }

    public void printUsage() {
        System.out.println(USAGE);
    }

    public static String getUsage() {
        return USAGE;
    }

    private void printArgs() {
        String backendsStr = backends != null
                ? backends.stream().map(Objects::toString).collect(Collectors.joining(","))
                : null;
        System.out.printf((PRINT_ARGS_FORMAT) + "%n", host, port, properties, main, backendsStr, reportDir, statsDir);
    }

    public String getHost() {
        return host;
    }

    public int getPort() {
        return port;
    }

    public String getProperties() {
        return properties;
    }

    public boolean isMain() {
        return main;
    }

    public String getStatsDir() {
        return statsDir;
    }

    public String getReportDir() {
        return reportDir;
    }

    public List<Backend> getBackends() {
        return backends;
    }

    public static final class Backend {
        private String host;
        private int port;

        public Backend(String host, int port) {
            this.host = host;
            this.port = port;
        }

        public Backend(String host) {
            this(host, 80);
        }

        public String getHost() {
            return host;
        }

        public int getPort() {
            return port;
        }

        @Override
        public String toString() {
            return host + ":" + port;
        }
    }
}
