FROM mcr.microsoft.com/devcontainers/php:8.3

# python stuff
# Install Python 3.10 dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libssl-dev \
    zlib1g-dev \
    libncurses5-dev \
    libncursesw5-dev \
    libreadline-dev \
    libsqlite3-dev \
    libgdbm-dev \
    libdb5.3-dev \
    libbz2-dev \
    libexpat1-dev \
    liblzma-dev \
    tk-dev \
    && rm -rf /var/lib/apt/lists/*

# Download Python 3.10 source
RUN curl -fsSL -o python.tar.xz "https://www.python.org/ftp/python/3.10.0/Python-3.10.0.tar.xz" \
    && mkdir -p /usr/src/python \
    && tar -xf python.tar.xz -C /usr/src/python --strip-components=1 \
    && rm python.tar.xz

# Compile and install Python 3.10
RUN cd /usr/src/python \
    && ./configure --enable-optimizations \
    && make -j "$(nproc)" \
    && make altinstall

# Clean up
RUN rm -rf /usr/src/python \
    && apt-get remove -y build-essential \
    && apt-get autoremove -y \
    && rm -rf /var/lib/apt/lists/*

# Set Python 3.10 as default
RUN update-alternatives --install /usr/bin/python3 python3 /usr/local/bin/python3.10 1


# threads stuff
RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql

RUN docker-php-ext-configure pcntl --enable-pcntl \
  && docker-php-ext-install \
    pcntl

RUN sed -i '/cs_CZ.UTF-8/s/^# //g' /etc/locale.gen && \
    locale-gen
ENV LANG cs_CZ.UTF-8
ENV LC_ALL cs_CZ.UTF-8

RUN apt-get update && apt-get install -y \
    dos2unix \
    && rm -rf /var/lib/apt/lists/*
