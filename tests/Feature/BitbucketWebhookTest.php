<?php

namespace Tests\Feature;

use App\Issue;
use App\Jobs\InitializeRepo;
use App\Repository;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

/** @group cloud */
class BitbucketWebhookTest extends TestCase
{
    use RefreshDatabase;

   /** @test */
   public function it_can_crate_an_issue(){
        $payload = json_decode('{
  "issue": {
    "content": {
      "raw": "",
      "markup": "markdown",
      "html": "",
      "type": "rendered"
    },
    "kind": "bug",
    "links": {
      "attachments": {
        "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back/issues/703/attachments"
      },
      "self": {
        "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back/issues/703"
      },
      "watch": {
        "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back/issues/703/watch"
      },
      "comments": {
        "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back/issues/703/comments"
      },
      "html": {
        "href": "https://bitbucket.org/revo-pos/revo-back/issues/703/prova-new-issue"
      },
      "vote": {
        "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back/issues/703/vote"
      }
    },
    "reporter": {
      "username": "BadChoice",
      "display_name": "Jordi Puigdellivol",
      "account_id": "557058:1ed68706-8e6a-4226-9c00-878030ad69b6",
      "links": {
        "self": {
          "href": "https://api.bitbucket.org/2.0/users/BadChoice"
        },
        "html": {
          "href": "https://bitbucket.org/BadChoice/"
        },
        "avatar": {
          "href": "https://bitbucket.org/account/BadChoice/avatar/"
        }
      },
      "type": "user",
      "nickname": "BadChoice",
      "uuid": "{4f024e7b-f697-4151-81e0-1a5178f8c6d4}"
    },
    "title": "Prova new issue",
    "component": null,
    "votes": 0,
    "watches": 1,
    "priority": "major",
    "assignee": null,
    "state": "new",
    "version": {
      "name": "1.2",
      "links": {
        "self": {
          "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back/versions/138503"
        }
      }
    },
    "edited_on": null,
    "created_on": "2018-11-14T12:03:42.658716+00:00",
    "milestone": null,
    "updated_on": "2018-11-14T12:03:42.658716+00:00",
    "type": "issue",
    "id": 703
  },
  "actor": {
    "username": "BadChoice",
    "display_name": "Jordi Puigdellivol",
    "account_id": "557058:1ed68706-8e6a-4226-9c00-878030ad69b6",
    "links": {
      "self": {
        "href": "https://api.bitbucket.org/2.0/users/BadChoice"
      },
      "html": {
        "href": "https://bitbucket.org/BadChoice/"
      },
      "avatar": {
        "href": "https://bitbucket.org/account/BadChoice/avatar/"
      }
    },
    "type": "user",
    "nickname": "BadChoice",
    "uuid": "{4f024e7b-f697-4151-81e0-1a5178f8c6d4}"
  },
  "repository": {
    "scm": "git",
    "website": "",
    "name": "revo-back",
    "links": {
      "self": {
        "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back"
      },
      "html": {
        "href": "https://bitbucket.org/revo-pos/revo-back"
      },
      "avatar": {
        "href": "https://bytebucket.org/ravatar/%7Bdc93fbcd-d9db-4feb-ad91-5aaa7c71804e%7D?ts=418466"
      }
    },
    "project": {
      "links": {
        "self": {
          "href": "https://api.bitbucket.org/2.0/teams/revo-pos/projects/XEF"
        },
        "html": {
          "href": "https://bitbucket.org/account/user/revo-pos/projects/XEF"
        },
        "avatar": {
          "href": "https://bitbucket.org/account/user/revo-pos/projects/XEF/avatar/32"
        }
      },
      "type": "project",
      "uuid": "{4240b083-ceac-4940-9ecc-9d2b903017bc}",
      "key": "XEF",
      "name": "RevoXef"
    },
    "full_name": "revo-pos/revo-back",
    "owner": {
      "username": "revo-pos",
      "type": "team",
      "display_name": "Revo",
      "uuid": "{6fa4ada1-2d50-4aaf-94bc-5fffb9d4504f}",
      "links": {
        "self": {
          "href": "https://api.bitbucket.org/2.0/teams/revo-pos"
        },
        "html": {
          "href": "https://bitbucket.org/revo-pos/"
        },
        "avatar": {
          "href": "https://bitbucket.org/account/revo-pos/avatar/"
        }
      }
    },
    "type": "repository",
    "is_private": true,
    "uuid": "{dc93fbcd-d9db-4feb-ad91-5aaa7c71804e}"
  }
}', true);

       $this->withoutExceptionHandling();
       $repository = factory(Repository::class)->create(['account' => 'revo-pos', 'repo' => 'revo-back']);
       //$issue = factory(Issue::class)->create(["issue_id" => 702, "title" => "hello", "repository_id" => $repository->id]);

       $response = $this->post('webhook', $payload);

       $response->assertStatus(Response::HTTP_OK);
       $this->assertCount(1, Issue::all());
       tap (Issue::first(), function($issue) use($repository){
           $this->assertEquals('Prova new issue', $issue->title);
           $this->assertEquals($repository->id, $issue->repository_id);
       });
   }

   /** @test */
   public function an_issue_already_created_is_just_updated_when_receiving_the_created_event(){
       $payload = json_decode('{
  "issue": {
    "content": {
      "raw": "",
      "markup": "markdown",
      "html": "",
      "type": "rendered"
    },
    "kind": "bug",
    "links": {
      "attachments": {
        "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back/issues/703/attachments"
      },
      "self": {
        "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back/issues/703"
      },
      "watch": {
        "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back/issues/703/watch"
      },
      "comments": {
        "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back/issues/703/comments"
      },
      "html": {
        "href": "https://bitbucket.org/revo-pos/revo-back/issues/703/prova-new-issue"
      },
      "vote": {
        "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back/issues/703/vote"
      }
    },
    "reporter": {
      "username": "BadChoice",
      "display_name": "Jordi Puigdellivol",
      "account_id": "557058:1ed68706-8e6a-4226-9c00-878030ad69b6",
      "links": {
        "self": {
          "href": "https://api.bitbucket.org/2.0/users/BadChoice"
        },
        "html": {
          "href": "https://bitbucket.org/BadChoice/"
        },
        "avatar": {
          "href": "https://bitbucket.org/account/BadChoice/avatar/"
        }
      },
      "type": "user",
      "nickname": "BadChoice",
      "uuid": "{4f024e7b-f697-4151-81e0-1a5178f8c6d4}"
    },
    "title": "Prova new issue",
    "component": null,
    "votes": 0,
    "watches": 1,
    "priority": "major",
    "assignee": null,
    "state": "new",
    "version": {
      "name": "1.2",
      "links": {
        "self": {
          "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back/versions/138503"
        }
      }
    },
    "edited_on": null,
    "created_on": "2018-11-14T12:03:42.658716+00:00",
    "milestone": null,
    "updated_on": "2018-11-14T12:03:42.658716+00:00",
    "type": "issue",
    "id": 703
  },
  "actor": {
    "username": "BadChoice",
    "display_name": "Jordi Puigdellivol",
    "account_id": "557058:1ed68706-8e6a-4226-9c00-878030ad69b6",
    "links": {
      "self": {
        "href": "https://api.bitbucket.org/2.0/users/BadChoice"
      },
      "html": {
        "href": "https://bitbucket.org/BadChoice/"
      },
      "avatar": {
        "href": "https://bitbucket.org/account/BadChoice/avatar/"
      }
    },
    "type": "user",
    "nickname": "BadChoice",
    "uuid": "{4f024e7b-f697-4151-81e0-1a5178f8c6d4}"
  },
  "repository": {
    "scm": "git",
    "website": "",
    "name": "revo-back",
    "links": {
      "self": {
        "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back"
      },
      "html": {
        "href": "https://bitbucket.org/revo-pos/revo-back"
      },
      "avatar": {
        "href": "https://bytebucket.org/ravatar/%7Bdc93fbcd-d9db-4feb-ad91-5aaa7c71804e%7D?ts=418466"
      }
    },
    "project": {
      "links": {
        "self": {
          "href": "https://api.bitbucket.org/2.0/teams/revo-pos/projects/XEF"
        },
        "html": {
          "href": "https://bitbucket.org/account/user/revo-pos/projects/XEF"
        },
        "avatar": {
          "href": "https://bitbucket.org/account/user/revo-pos/projects/XEF/avatar/32"
        }
      },
      "type": "project",
      "uuid": "{4240b083-ceac-4940-9ecc-9d2b903017bc}",
      "key": "XEF",
      "name": "RevoXef"
    },
    "full_name": "revo-pos/revo-back",
    "owner": {
      "username": "revo-pos",
      "type": "team",
      "display_name": "Revo",
      "uuid": "{6fa4ada1-2d50-4aaf-94bc-5fffb9d4504f}",
      "links": {
        "self": {
          "href": "https://api.bitbucket.org/2.0/teams/revo-pos"
        },
        "html": {
          "href": "https://bitbucket.org/revo-pos/"
        },
        "avatar": {
          "href": "https://bitbucket.org/account/revo-pos/avatar/"
        }
      }
    },
    "type": "repository",
    "is_private": true,
    "uuid": "{dc93fbcd-d9db-4feb-ad91-5aaa7c71804e}"
  }
}', true);

       $this->withoutExceptionHandling();
       $repository = factory(Repository::class)->create(['account' => 'revo-pos', 'repo' => 'revo-back']);
       $issue = factory(Issue::class)->create(["issue_id" => 703, "title" => "hello", "repository_id" => $repository->id]);

       $response = $this->post('webhook', $payload);

       $response->assertStatus(Response::HTTP_OK);
       $this->assertCount(1, Issue::all());
       tap (Issue::first(), function($issue) use($repository){
           $this->assertEquals('Prova new issue', $issue->title);
           $this->assertEquals($repository->id, $issue->repository_id);
       });
   }

    /** @test */
    public function an_issue_can_be_updated(){

        $payload = json_decode('{
  "comment": {
    "links": {
      "self": {
        "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back/issues/702/comments/48927861"
      },
      "html": {
        "href": "https://bitbucket.org/revo-pos/revo-back/issues/702#comment-48927861"
      }
    },
    "content": {
      "raw": null,
      "markup": "markdown",
      "html": "",
      "type": "rendered"
    },
    "created_on": "2018-11-14T11:50:24.328241+00:00",
    "user": {
      "username": "BadChoice",
      "display_name": "Jordi Puigdellivol",
      "account_id": "557058:1ed68706-8e6a-4226-9c00-878030ad69b6",
      "links": {
        "self": {
          "href": "https://api.bitbucket.org/2.0/users/BadChoice"
        },
        "html": {
          "href": "https://bitbucket.org/BadChoice/"
        },
        "avatar": {
          "href": "https://bitbucket.org/account/BadChoice/avatar/"
        }
      },
      "type": "user",
      "nickname": "BadChoice",
      "uuid": "{4f024e7b-f697-4151-81e0-1a5178f8c6d4}"
    },
    "updated_on": null,
    "type": "issue_comment",
    "id": 48927861
  },
  "changes": {
    "title": {
      "new": "Prova amb tags 2",
      "old": "prova amb tags"
    }
  },
  "issue": {
    "content": {
      "raw": "",
      "markup": "markdown",
      "html": "",
      "type": "rendered"
    },
    "kind": "task",
    "repository": {
      "full_name": "revo-pos/revo-back",
      "type": "repository",
      "name": "revo-back",
      "links": {
        "self": {
          "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back"
        },
        "html": {
          "href": "https://bitbucket.org/revo-pos/revo-back"
        },
        "avatar": {
          "href": "https://bytebucket.org/ravatar/%7Bdc93fbcd-d9db-4feb-ad91-5aaa7c71804e%7D?ts=418466"
        }
      },
      "uuid": "{dc93fbcd-d9db-4feb-ad91-5aaa7c71804e}"
    },
    "links": {
      "attachments": {
        "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back/issues/702/attachments"
      },
      "self": {
        "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back/issues/702"
      },
      "watch": {
        "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back/issues/702/watch"
      },
      "comments": {
        "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back/issues/702/comments"
      },
      "html": {
        "href": "https://bitbucket.org/revo-pos/revo-back/issues/702/prova-amb-tags-2"
      },
      "vote": {
        "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back/issues/702/vote"
      }
    },
    "title": "Prova amb tags 2",
    "reporter": {
      "username": "BadChoice",
      "display_name": "Jordi Puigdellivol",
      "account_id": "557058:1ed68706-8e6a-4226-9c00-878030ad69b6",
      "links": {
        "self": {
          "href": "https://api.bitbucket.org/2.0/users/BadChoice"
        },
        "html": {
          "href": "https://bitbucket.org/BadChoice/"
        },
        "avatar": {
          "href": "https://bitbucket.org/account/BadChoice/avatar/"
        }
      },
      "type": "user",
      "nickname": "BadChoice",
      "uuid": "{4f024e7b-f697-4151-81e0-1a5178f8c6d4}"
    },
    "component": null,
    "votes": 0,
    "watches": 1,
    "priority": "major",
    "assignee": {
      "username": "PauRevo",
      "display_name": "Pau Benet Prat",
      "account_id": "557058:a29e0c4c-2c1b-476d-a3b6-ef4728cdba79",
      "links": {
        "self": {
          "href": "https://api.bitbucket.org/2.0/users/PauRevo"
        },
        "html": {
          "href": "https://bitbucket.org/PauRevo/"
        },
        "avatar": {
          "href": "https://bitbucket.org/account/PauRevo/avatar/"
        }
      },
      "type": "user",
      "nickname": "PauRevo",
      "uuid": "{002f0676-719e-4930-b9b1-0b67bf815279}"
    },
    "state": "new",
    "version": null,
    "edited_on": null,
    "created_on": "2018-11-14T10:53:18.701321+00:00",
    "milestone": null,
    "updated_on": "2018-11-14T11:50:24.296867+00:00",
    "type": "issue",
    "id": 702
  },
  "actor": {
    "username": "BadChoice",
    "display_name": "Jordi Puigdellivol",
    "account_id": "557058:1ed68706-8e6a-4226-9c00-878030ad69b6",
    "links": {
      "self": {
        "href": "https://api.bitbucket.org/2.0/users/BadChoice"
      },
      "html": {
        "href": "https://bitbucket.org/BadChoice/"
      },
      "avatar": {
        "href": "https://bitbucket.org/account/BadChoice/avatar/"
      }
    },
    "type": "user",
    "nickname": "BadChoice",
    "uuid": "{4f024e7b-f697-4151-81e0-1a5178f8c6d4}"
  },
  "repository": {
    "scm": "git",
    "website": "",
    "name": "revo-back",
    "links": {
      "self": {
        "href": "https://api.bitbucket.org/2.0/repositories/revo-pos/revo-back"
      },
      "html": {
        "href": "https://bitbucket.org/revo-pos/revo-back"
      },
      "avatar": {
        "href": "https://bytebucket.org/ravatar/%7Bdc93fbcd-d9db-4feb-ad91-5aaa7c71804e%7D?ts=418466"
      }
    },
    "project": {
      "links": {
        "self": {
          "href": "https://api.bitbucket.org/2.0/teams/revo-pos/projects/XEF"
        },
        "html": {
          "href": "https://bitbucket.org/account/user/revo-pos/projects/XEF"
        },
        "avatar": {
          "href": "https://bitbucket.org/account/user/revo-pos/projects/XEF/avatar/32"
        }
      },
      "type": "project",
      "uuid": "{4240b083-ceac-4940-9ecc-9d2b903017bc}",
      "key": "XEF",
      "name": "RevoXef"
    },
    "full_name": "revo-pos/revo-back",
    "owner": {
      "username": "revo-pos",
      "type": "team",
      "display_name": "Revo",
      "uuid": "{6fa4ada1-2d50-4aaf-94bc-5fffb9d4504f}",
      "links": {
        "self": {
          "href": "https://api.bitbucket.org/2.0/teams/revo-pos"
        },
        "html": {
          "href": "https://bitbucket.org/revo-pos/"
        },
        "avatar": {
          "href": "https://bitbucket.org/account/revo-pos/avatar/"
        }
      }
    },
    "type": "repository",
    "is_private": true,
    "uuid": "{dc93fbcd-d9db-4feb-ad91-5aaa7c71804e}"
  }
}', true);

        $this->withoutExceptionHandling();
        $repository = factory(Repository::class)->create(['account' => 'revo-pos', 'repo' => 'revo-back']);
        $issue = factory(Issue::class)->create(["issue_id" => 702, "title" => "hello", "repository_id" => $repository->id]);

        $response = $this->post('webhook', $payload);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals('Prova amb tags 2', $issue->fresh()->title);
   }
}
