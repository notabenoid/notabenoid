# Notabenoid

Notabenoid is a web service for collaborative translation.

## Running the site

The simplest way to run the application is to use Docker. Install Docker using your package manager and start the Docker server (`# /etc/init.d/docker start`) and execute the following command (from the root account or from an account of a user belonging to the `docker` group):

    docker run --rm -p 127.0.0.1:8080:80 opennota/notabenoid

Docker will download the prebuilt image from the Docker Hub, create a container, and start it. After it is running go to the following link: [http://localhost:8080](http://localhost:8080). Log in as admin/admin and change the password.

`--rm` means that the container will be removed after it is stopped.

`-p 127.0.0.1:8080:80` means that the application will be listening on the local port 8080.

You can also build the image yourself, using the repository [notabenoid/notabenoid-dockerfile](https://github.com/notabenoid/notabenoid-dockerfile).

