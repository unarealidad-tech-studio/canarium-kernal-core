
DROP PROCEDURE IF EXISTS roles;
CREATE PROCEDURE roles()
BEGIN

	IF NOT EXISTS(
		SELECT * FROM role WHERE role_id = 'user'
	) THEN
		INSERT INTO role (id, parent_id, role_id) VALUES (1, NULL, 'user');
	END IF;

	IF NOT EXISTS(
		SELECT * FROM role WHERE role_id = 'owner'
	) THEN
		INSERT INTO role (id, parent_id, role_id) VALUES (2, 1, 'owner');
	END IF;

	IF NOT EXISTS(
		SELECT * FROM role WHERE role_id = 'admin'
	) THEN
		INSERT INTO role (id, parent_id, role_id) VALUES (3, 2, 'admin');
	END IF;

	IF NOT EXISTS(
		SELECT * FROM role WHERE role_id = 'superuser'
	) THEN
		INSERT INTO role (id, parent_id, role_id) VALUES (4, 3, 'superuser');
	END IF;

END;

CALL roles();

DROP PROCEDURE IF EXISTS roles;

DROP PROCEDURE IF EXISTS preliminary_users;
CREATE PROCEDURE preliminary_users()
BEGIN

	IF NOT EXISTS(
		SELECT * FROM user WHERE id = 1 AND email = 'root@root.com'
	) THEN
		INSERT INTO user (id, email, password) VALUES (1, 'root@root.com', '$2y$14$L6vwfmgkj7CparbgvB4HV.jT5gkUQTrxlxfqcUOOaJr8/I4AmXgtK');
    INSERT INTO user_role_linker (user_id, role_id) VALUES (1, 4);
	END IF;

	IF NOT EXISTS(
		SELECT * FROM user WHERE id = 2 AND email = 'admin@admin.com'
	) THEN
    INSERT INTO user (id, email, password) VALUES (2, 'admin@admin.com', '$2y$14$L6vwfmgkj7CparbgvB4HV.jT5gkUQTrxlxfqcUOOaJr8/I4AmXgtK');
    INSERT INTO user_role_linker (user_id, role_id) VALUES (2, 3);
	END IF;

END;

CALL preliminary_users();



