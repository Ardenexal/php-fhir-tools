# Use official PHP 8.3 image with CLI and development tools
FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    wget \
    gnupg2 \
    software-properties-common \
    ca-certificates \
    lsb-release \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required by the project
RUN docker-php-ext-install \
    ctype \
    iconv \
    zip \
    intl \
    mbstring \
    xml

# Install Xdebug for debugging and coverage
RUN pecl install xdebug-3.3.1 \
    && docker-php-ext-enable xdebug

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create a non-root user
ARG USERNAME=vscode
ARG USER_UID=1000
ARG USER_GID=$USER_UID

RUN groupadd --gid $USER_GID $USERNAME \
    && useradd --uid $USER_UID --gid $USER_GID -m $USERNAME \
    && apt-get update \
    && apt-get install -y sudo \
    && echo $USERNAME ALL=\(root\) NOPASSWD:ALL > /etc/sudoers.d/$USERNAME \
    && chmod 0440 /etc/sudoers.d/$USERNAME

# Set up Composer cache directory
RUN mkdir -p /home/$USERNAME/.composer \
    && chown -R $USERNAME:$USERNAME /home/$USERNAME/.composer

# Install Node.js (useful for any frontend tooling)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install additional development tools
RUN apt-get update && apt-get install -y \
    vim \
    nano \
    htop \
    tree \
    jq \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /workspace

# Switch to non-root user
USER $USERNAME

# Set up shell environment
RUN echo 'export PATH="$PATH:/workspace/vendor/bin"' >> /home/$USERNAME/.bashrc \
    && echo 'alias ll="ls -la"' >> /home/$USERNAME/.bashrc \
    && echo 'alias la="ls -la"' >> /home/$USERNAME/.bashrc \
    && echo 'alias phpunit="./vendor/bin/phpunit"' >> /home/$USERNAME/.bashrc \
    && echo 'alias phpstan="./vendor/bin/phpstan.phar"' >> /home/$USERNAME/.bashrc \
    && echo 'alias pint="./vendor/bin/pint"' >> /home/$USERNAME/.bashrc

# Configure Git (will be overridden by user's git config)
RUN git config --global init.defaultBranch main \
    && git config --global pull.rebase false