FROM php:8.2-cli

RUN apt-get update && apt-get install -y curl

WORKDIR /app

COPY . /app

ENTRYPOINT ["php"]

CMD ["app.php"]