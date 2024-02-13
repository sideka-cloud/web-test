# Clone Repository
````swift
git clone https://github.com/sideka-cloud/web-test.git
````

# Build Image
````swift
docker build -t web-test:latest .
````

# Deploy Container
````swift
docker run -d -p 80:80 --name web-test web-test:latest
````
