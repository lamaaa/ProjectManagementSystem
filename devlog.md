# 帐号分类

## 超级管理员
## 产品经理
## 设计师
## 程序员

# 模块
## 登录模块
url和对应的控制器方法：
登录界面：/auth/login
成功后跳转：/redirect
判断登录用户拥有的身份：
admin(超级管理员):/admin/index/
pm(项目经理):/pm/index/
commonUser（）


## 客户模块
列出所有客户

权限拥有者：超级管理员、相应负责的产品经理

url和对应的控制器方法：

get /customer/index CustomerController@index

控制器方法逻辑：
1. 检查用户是否拥有“admin”或者“pm”的角色，没有则拒绝访问
2. 区分出超级管理员和相应负责的产品经理
3. 超级管理员返回所有客户
4. 相应负责的产品经理返回相应负责的客户

### 添加客户
添加新的客户

权限拥有者：超级管理员

url和对应的控制器方法：

get /customer/create CustomerController@create

控制器方法逻辑：
1. 检查是否拥有“admin”角色，没有则拒绝访问
2. 返回添加用户的表单

post /customer CustomerController@store

控制器方法逻辑：
1. 检查是否拥有“admin”角色，没有则拒绝访问
2. 添加用户

### 修改客户
修改客户细节

权限拥有者：超级管理员、相应负责的产品经理

url和对应的控制器方法：

get /customer/{customer}/edit CustomerController@edit

控制器方法逻辑：
1. 检查用户是否拥有“admin”或者“pm”的角色，没有则拒绝访问
2. 返回更新用户的表单

put /customer/{customer} CustomerController@update

控制器方法逻辑：
1. 检查是否拥有“admin”角色，没有则拒绝访问
2. 更新用户

控制器方法逻辑：
1. 检查是否拥有“admin”角色，没有则拒绝访问
2. 添加用户


### 删除客户
删除客户

权限拥有者：超级管理员

url和对应的控制器方法：

delete /customer/{customer} CustomerController@destroy

控制器方法逻辑：
1. 检查用户是否拥有“admin”角色，没有则拒绝访问
2. 删除用户

### 查看客户
查看客户的细节

权限拥有者：超级管理员、相应负责的产品经理

url和对应的控制器方法：

get /customer/{customer} CustomerController@show

控制器方法逻辑：
1. 检查用户是否拥有“admin”或者“pm”的角色，没有则拒绝访问
2. 查看用户 

## 项目模块
列出所有项目

权限拥有者：超级管理员、相应负责的产品经理

url和对应的控制器方法：

get /project/index ProjectController@index

控制器方法逻辑：
1. 检查用户是否拥有“admin”或者“pm”的角色，没有则拒绝访问
2. 区分出超级管理员和相应负责的产品经理
3. 超级管理员返回所有项目
4. 相应负责的产品经理返回相应负责的项目

### 添加项目
添加新的项目

权限拥有者：超级管理员、相应负责的产品经理

url和对应的控制器方法：

get /project/create ProjectController@create

控制器方法逻辑：
1. 检查是否拥有“admin”或者“pm”角色，没有则拒绝访问
2. 超级管理员返回包含选择项目经理的表单
3. 项目经理返回不包含项目经理的表单

post /project ProjectController@store

控制器方法逻辑：
1. 检查是否拥有“admin”或者“pm”角色，没有则拒绝访问
2. 超级管理员直接添加项目
3. 项目经理作为本项目项目经理添加项目

### 修改项目
修改客户细节

权限拥有者：超级管理员、相应负责的产品经理

url和对应的控制器方法：

get /project/{project}/edit ProjectController@edit

控制器方法逻辑：
1. 检查是否拥有“admin”或者“pm”角色，没有则拒绝访问
2. 超级管理员允许修改项目经理选项
3. 项目经理不允许修改项目经理选项

put /project/{project} ProjectController@update

控制器方法逻辑：


### 删除项目
删除项目

权限拥有者：超级管理员、相应负责的产品经理

url和对应的控制器方法：

delete /project/{project} ProjectController@destroy

### 查看项目
查看客户的细节

权限拥有者：超级管理员、相应负责的产品经理、相应参与的程序员和设计师

url和对应的控制器方法：

get /project/{project} ProjectController@show

## 成员模块
列出所有成员

权限拥有者：超级管理员

url和对应的控制器方法：

get /user/index UserController@index

控制器方法逻辑：
1. 检查是否拥有“admin”

### 添加成员
添加新的成员

权限拥有者：超级管理员

url和对应的控制器方法：

get /user/create UserController@create
post /user UserController@store

### 修改成员
修改客户细节

权限拥有者：超级管理员

url和对应的控制器方法：

get /user/{user}/edit UserController@edit
put /user/{user} UserController@update

### 删除成员
删除成员

权限拥有者：超级管理员

url和对应的控制器方法：

delete /user/{user} UserController@destroy

### 查看成员
查看客户的细节

权限拥有者：超级管理员

url和对应的控制器方法：

get /user/{user} UserController@show


