FROM whitis01/norway
COPY . /var/www/html
RUN echo "cd /var/www/html" >> ~/.bashrc

