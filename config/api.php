<?php

return array(
    "webhook_methods" => ['created', 'deleted', 'updated'],
    //  Basic API methods
    'docs' => [
        array(
            'name' => 'Users',
            'methods' => array(
                array(
                    'title' => 'Get Users',
                    'url' => 'users',
                    'methods' => array(
                        array(
                            'name' => 'get',
                            'color' => 'green'
                        ),
                    ),
                    'example' => array(
                        'success' => '{"data": [{"id": "29","alias": "supawiz6991","is_admin": false}],"paginator": {"total_count": 34,"total_pages": 2,"current_page": 1,"limit": 20}}',
                        'error' => 'Not applicable.'
                    ),
                    'description' => 'This endpoint will return all users. Limited 20 users per page.',
                    'params' => array('limit INT', 'page INT')
                ),
                array(
                    'title' => 'Get Specific User',
                    'url' => 'users/{id}',
                    'methods' => array(
                        array(
                            'name' => 'get',
                            'color' => 'green'
                        ),
                    ),
                    'example' => array(
                        'success' => '{"data":{"id":"1","alias":"lDucks","is_admin":true}}',
                        'error' => '{"error":{"message":"User does not exist!","status_code":404}}'
                    ),
                    'description' => 'This endpoint will return a single specified user.',
                )
            )
        ),
        array(
            'name' => 'BattleTasks',
            'methods' => array(
                array(
                    'title' => 'Get Tasks',
                    'url' => 'tasks',
                    'methods' => array(
                        array(
                            'name' => 'get',
                            'color' => 'green'
                        ),
                        array(
                            'name' => 'requires auth',
                            'color' => 'black'
                        )
                    ),
                    'example' => array(
                        'success' => '{"data":[{"id":"36","title":"Firmissimum eu","content":"Ullamco occaecat deserunt illustriora consectetur tractavissent sint dolore culpa esse occaecat consectetur commodo relinqueret sunt magna ex praesentibus nostrud quibusdam cupidatat sunt esse veniam hic quibusdam lorem laboris a malis","creator":"lDucks","assigned_to":"Zach443","public":false,"completed":false}],"paginator":{"total_count":34,"total_pages":2,"current_page":1,"limit":20}}',
                        'error' => '{"error":{"message":"Failed to validate API-Key.","status_code":422}}'
                    ),
                    'description' => 'This endpoint will return all tasks, regardless of status (open or completed). Limited 20 tasks per page.',
                    'params' => array('limit INT', 'page INT')
                ),
                array(
                    'title' => 'Get Specific Task',
                    'url' => 'tasks/{id}',
                    'methods' => array(
                        array(
                            'name' => 'get',
                            'color' => 'green'
                        ),
                        array(
                            'name' => 'requires auth',
                            'color' => 'black'
                        )
                    ),
                    'example' => array(
                        'success' => '{"data":{"id":"36","title":"Firmissimum eu","content":"Ullamco occaecat deserunt illustriora consectetur tractavissent sint dolore culpa esse occaecat consectetur commodo relinqueret sunt magna ex praesentibus nostrud quibusdam cupidatat sunt esse veniam hic quibusdam lorem laboris a malis","creator":"lDucks","assigned_to":"Zach443","public":false,"completed":false}}',
                        'error' => '{"error":{"message":"Task does not exist!","status_code":404}}'
                    ),
                    'description' => 'This endpoint will return a single specified task, regardless of status (open or completed).',
                ),
                array(
                    'title' => 'Create Task',
                    'url' => 'tasks',
                    'methods' => array(
                        array(
                            'name' => 'post',
                            'color' => 'red'
                        ),
                        array(
                            'name' => 'requires auth',
                            'color' => 'black'
                        )
                    ),
                    'example' => array(
                        'success' => '{"success":{"message":"Task successfully created.","status_code":201}}',
                        'error' => '{"error":{"message":"Failed to validate API-Key.","status_code":422}}'
                    ),
                    'description' => 'This endpoint will create a new task.',
                    'params' => array('title VARCHAR(64)', 'assigned_to INT(11)', 'public BOOLEAN()', 'content TEXT()', 'status BOOLEAN()'),
                ),
                array(
                    'title' => 'Edit Task',
                    'url' => 'tasks/{id}',
                    'methods' => array(
                        array(
                            'name' => 'put/patch',
                            'color' => 'orange'
                        ),
                        array(
                            'name' => 'requires auth',
                            'color' => 'black'
                        )
                    ),
                    'example' => array(
                        'success' => '{"success":{"message":"Task has been modified.","status_code":200}}',
                        'error' => '{"error":{"message":"Task does not exist!","status_code":404}}'
                    ),
                    'description' => 'This endpoint will edit an existing task.',
                    'params' => array('title VARCHAR(64)', 'assigned_to INT(11)', 'public BOOLEAN()', 'content TEXT()', 'status BOOLEAN()'),
                ),
                array(
                    'title' => 'Delete Task',
                    'url' => 'tasks/{id}',
                    'methods' => array(
                        array(
                            'name' => 'destroy',
                            'color' => 'purple'
                        ),
                        array(
                            'name' => 'requires auth',
                            'color' => 'black'
                        )
                    ),
                    'example' => array(
                        'success' => '{"success":{"message":"Task has been deleted.","status_code":201}}',
                        'error' => '{"error":{"message":"Failed to validate API-Key.","status_code":422}}'
                    ),
                    'description' => 'This endpoint will delete a specified task.'
                )
            )
        ),
        array(
            'name' => 'BattleBlog',
            'methods' => array(
                array(
                    'title' => 'Get Blogs',
                    'url' => 'blogs',
                    'methods' => array(
                        array(
                            'name' => 'get',
                            'color' => 'green'
                        ),
                        array(
                            'name' => 'requires auth',
                            'color' => 'black'
                        )
                    ),
                    'example' => array(
                        'success' => '{"data":[{"id":7,"title":"Testing","content":"This is a test","author":"lDucks","created_at":{"date":"2015-06-09 16:36:56.000000","timezone_type":3,"timezone":"UTC"},"updated_at":{"date":"2015-06-11 23:20:33.000000","timezone_type":3,"timezone":"UTC"}}],"paginator":{"total_count":3,"total_pages":1,"current_page":1,"limit":5}}',
                        'error' => 'Not Applicable.'
                    ),
                    'description' => 'This endpoint will return all blog posts. Limited 5 posts per page.',
                    'params' => array('limit INT', 'page INT')
                ),
                array(
                    'title' => 'Get Specific Blog',
                    'url' => 'tasks/{id}',
                    'methods' => array(
                        array(
                            'name' => 'get',
                            'color' => 'green'
                        )
                    ),
                    'example' => array(
                        'success' => '{"data":{"id":7,"title":"Testing","content":"This is a test","author":"lDucks","created_at":{"date":"2015-06-09 16:36:56.000000","timezone_type":3,"timezone":"UTC"},"updated_at":{"date":"2015-06-11 23:20:33.000000","timezone_type":3,"timezone":"UTC"}}}',
                        'error' => '{"error":{"message":"Blog does not exist!","status_code":404}}'
                    ),
                    'description' => 'This endpoint will return a single specified blog post.',
                ),
                array(
                    'title' => 'Create Blog',
                    'url' => 'blogs',
                    'methods' => array(
                        array(
                            'name' => 'post',
                            'color' => 'red'
                        ),
                        array(
                            'name' => 'requires auth',
                            'color' => 'black'
                        )
                    ),
                    'example' => array(
                        'success' => '{"success":{"message":"Blog successfully created.","status_code":201}}',
                        'error' => '{"error":{"message":"A required field has been left blank.","status_code":200}}'
                    ),
                    'description' => 'This endpoint will create a new blog post.',
                    'params' => array('title VARCHAR(64)', 'author INT(11)', 'content TEXT()'),
                ),
                array(
                    'title' => 'Edit Blog',
                    'url' => 'blogs/{id}',
                    'methods' => array(
                        array(
                            'name' => 'put/patch',
                            'color' => 'orange'
                        ),
                        array(
                            'name' => 'requires auth',
                            'color' => 'black'
                        )
                    ),
                    'example' => array(
                        'success' => '{"success":{"message":"Blog has been modified.","status_code":200}}',
                        'error' => '{"error":{"message":"Blog does not exist!","status_code":404}}'
                    ),
                    'description' => 'This endpoint will edit an existing blog post.',
                    'params' => array('title VARCHAR(64)', 'content TEXT()'),
                ),
                array(
                    'title' => 'Delete Blog',
                    'url' => 'blogs/{id}',
                    'methods' => array(
                        array(
                            'name' => 'destroy',
                            'color' => 'purple'
                        ),
                        array(
                            'name' => 'requires auth',
                            'color' => 'black'
                        )
                    ),
                    'example' => array(
                        'success' => '{"success":{"message":"Blog has been deleted.","status_code":201}}',
                        'error' => '{"error":{"message":"Failed to validate API-Key.","status_code":422}}'
                    ),
                    'description' => 'This endpoint will delete a specified blog post.'
                )
            )
        ),
        array(
            'name' => 'bplug.in',
            'methods' => array(
                array(
                    'title' => 'Create Short URL',
                    'url' => 'shorturls',
                    'methods' => array(
                        array(
                            'name' => 'post',
                            'color' => 'red'
                        ),
                        array(
                            'name' => 'requires auth',
                            'color' => 'black'
                        )
                    ),
                    'example' => array(
                        'success' => '{"success":{"message":"BgmPUd","status_code":201}}',
                        'error' => '{"error":{"message":"Failed to validate API-Key.","status_code":422}}'
                    ),
                    'description' => 'This endpoint will create a short URL for the provided url. If the short url
				already exists, it will just return the URL. The shortened URL is returned under the "message" key.
				Append that key to the end of https://bplug.in to access it. IE) https://bplug.in/BgmPUd where BgmPUd
				 is the short url ID.',
                    'params' => array('url VARCHAR(255)')
                )
            )
        ),
    ]
);