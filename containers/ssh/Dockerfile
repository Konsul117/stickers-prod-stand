FROM ubuntu:latest

RUN apt-get update && apt-get install -y openssh-server
RUN mkdir /var/run/sshd
RUN echo 'root:screencast' | chpasswd
RUN sed -i 's/PermitRootLogin without-password/PermitRootLogin yes/' /etc/ssh/sshd_config

RUN echo "AuthorizedKeysFile	.ssh/authorized_keys" >> /etc/ssh/sshd_config

# SSH login fix. Otherwise user is kicked off after login
RUN sed 's@session\s*required\s*pam_loginuid.so@session optional pam_loginuid.so@g' -i /etc/pam.d/sshd

ENV NOTVISIBLE "in users profile"
RUN echo "export VISIBLE=now" >> /etc/profile

EXPOSE 22
CMD ["/usr/sbin/sshd", "-D"]

RUN sed -ri 's/#LogLevel INFO/LogLevel VERBOSE/g' /etc/ssh/sshd_config
RUN cd / \
	&& mkdir archives \
	&& cd archives \
	&& mkdir frontend \
	&& mkdir backend

RUN apt update && \
	apt install -y software-properties-common && \
	add-apt-repository -y ppa:ondrej/php && \
    apt update -y && \
    apt install -y \
        php7.2 \
        php7.2-xml \
        php7.2-intl \
        php7.2-cli \
        php7.2-dev \
        php7.2-mbstring \
        php7.2-bcmath \
        php7.2-zip \
        php7.2-memcache \
        php7.2-memcached \
        php7.2-apcu \
        php7.2-http \
        php7.2-mysqlnd \
        php7.2-pdo \
        php7.2-mysql \
        git \
        wget \
        curl \
        && \
    apt clean
