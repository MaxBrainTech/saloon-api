# jts-backend

# RUN on Local
`docker-compose -f dev-compose.yml up`

# deploy on server (docker and docker-compose are needed)
create network (one time only)

`docker network create --driver bridge shared`

`cd shared`

create proxy (one time only)

`docker-compose up -d`

`cd ..`

`docker-compose up -d`


# Update source codes
`docker-compose down`

`docker rmi $(docker images -q)`

`git pull origin master`

`docker-compose up -d --build`
