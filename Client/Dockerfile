# Build maven project
FROM maven:3.8.5-openjdk-11-slim AS build

COPY ./src /usr/src/app/src
COPY ./pom.xml /usr/src/app/pom.xml

RUN mvn -f /usr/src/app/pom.xml clean package

FROM ubuntu:bionic

RUN apt-get update

# RUBiS needed
RUN apt-get -y install gawk
RUN apt-get -y install sysstat

# SSH
RUN apt install openssh-server sudo -y
RUN useradd -rm -d /home/ubuntu -s /bin/bash -g root -G sudo -u 1000 test
RUN  echo 'test:test' | chpasswd
RUN service ssh start

# Copy files
RUN mkdir ./Client
ADD ./bench/** ./Client/bench/
COPY --from=build /usr/src/app/target/RUBIS-Client-*.jar ./Client/RUBiS-client-emulator.jar

EXPOSE 22

CMD ["/usr/sbin/sshd","-D"]