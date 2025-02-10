CREATE TABLE users(
	[id] [int] IDENTITY(1,1) NOT NULL,
	[name] [nvarchar](128) NOT NULL,
	[email] [nvarchar](128) NOT NULL,
	[password] [nvarchar](128) NOT NULL,
	[remember_token] [nvarchar](100) NULL,
	[default_url] [nvarchar](max) NULL,
	[foto] [nvarchar](max) NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL
) ON [PRIMARY] 


CREATE TABLE permissions (
  [id] [int] IDENTITY(1,1) NOT NULL,
  [title] [nvarchar](128) NULL,
  [created_at] [datetime] NULL,
  [updated_at] [datetime] NULL,
  [deleted_at] [datetime] NULL
) ON [PRIMARY]


GO

CREATE TABLE permission_role (
  [role_id] [int] NOT NULL,
  [permission_id] [int] NOT NULL
) 

CREATE TABLE role_user (
  [user_id] [int] NOT NULL,
  [role_id] [int] NOT NULL
) 

CREATE TABLE roles (
  [id] [int] IDENTITY(1,1) NOT NULL,
  [title] [nvarchar](128) NULL,
  [created_at] [datetime] NULL,
  [updated_at] [datetime] NULL,
  [deleted_at] [datetime] NULL
) ON [PRIMARY]