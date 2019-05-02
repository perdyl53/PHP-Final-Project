SELECT *
FROM clients
WHERE
	username = :username AND
	password = :password
	