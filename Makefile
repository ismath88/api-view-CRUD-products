ifndef APP_ENV
	include .env
endif

###> lexik/jwt-authentication-bundle ###
OPENSSL_BIN := $(shell which openssl)
generate-jwt-keys:
ifndef OPENSSL_BIN
	$(error "Unable to generate keys (needs OpenSSL)")
endif
	mkdir -p config/jwt
	openssl genrsa -passout pass:${JWT_PASSPHRASE} -out ${JWT_PRIVATE_KEY_PATH} -aes256 4096
	openssl rsa -passin pass:${JWT_PASSPHRASE} -pubout -in ${JWT_PRIVATE_KEY_PATH} -out ${JWT_PUBLIC_KEY_PATH}
	chmod 644 config/jwt/private.pem
	@echo "\033[32mRSA key pair successfully generated\033[39m"
###< lexik/jwt-authentication-bundle ###
