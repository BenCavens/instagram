Anitkythera mechanism
maintenance is good
- create README.md

Xdebug: xdebug_break();

XHPROF: profiling tool but helps 

git log -s <term>


Recommendation engine
---------------------

## Search engines: 
Fixed Document base
index analyzing the documents
calculate relevance for a query

## Recommender engine:
Document base is growing (netflix)
Query is static: find something I like

## Classification
- domain: news, products, ... (define what can be suggested)
- personalization levels: non personalized (best sellers)
- types of input: explicit (ask to rate), implicit (inferred from behaviour)
- Types of usage
    - prediction
    - recommendations
    - filtering
    
## MODEL
- user (with preferences)
- rating
- items 
- community

## TYPE: non-personalized: every user gets the same
e.g. bestseller, trending, most popular
MODEL: Item, rating, user

## TYPE: content-based
- look for simular items based on preference model
MODEL: 
problems: cold start, lack of serendipity

## TYPE: collaborative filtering
- user-user
- item-item
problems: sparsity (large matrix with low rating population), cold start (not enough info on preferences)
item cold start, scalability (slow computations)

-> calculate difference in users' ratings
-> take the avg of this difference
= score (weight) for each user towards you as user.
-> recommend what similar user have rated highly

use a K-nn algorithm (nearest neighbor)
Cosine similarity:
angle (cosine) between two ratings (vectors) = measures the difference

DOCKR
-----

### docker for mac: 
- native approach. uses lightweight virtualization (mac-os hypervisor)
- share without nfs
### docker toolbox: (use this to be the real devguy)
- nfs support with docker-machine-nfs
- not only 1 virtual machine

- add nfs: `brew install docker-machine-nfs` , `$ docker-machine-nfs docker-test` 
- inherits case insensitivity from shared nfs
- how ssh key?/ 
    - mount to container
    
MATTHIAS NOBACK -- OOP
----------------------
object encapsulates state and behaviour
some are focused more on state: value object, primitive values, domain object
focuses more on behaviour: services, value objects as well
entities have both

Find a balance: is this going to be a statefull object or just a 'service'

## Services should be modelled as functions. 
Most objects use just one method of the service.
We don't want an object to depend on the entire interface of a big object what methods it doesn't need to
So separate the methods: single method objects. Functions are naturally stateless (just as service objects should be)
function does an action and does not have a state.

Do not modify attributes the object in the service otherwise it would be statefull!
Due to lack of state changes, there is a smaller execution path and no unexpected changes in behaviour.

## Objects should be explicit about side-effects
e.g. logging something in service is a side effect, 
You can make it explicit by injecting the Logger as dependency

## Injected services should be single-method objects too
the method signature should make clear what's going on.
Adhere to law of demeter: 
Side-effects and responsibilities are explicit.
Dependencies are easy to mock

## Objects should only exist in a valid state
constructed in incomplete way or has bad data.
Temporal coupling: first do this method than do this. so in calling methodB it fails because methodA hasn't been called yet.
- canCallMethodB works but violated Tell don't ask
- isValid() via Validator
- no setters or getters!
logic is outside of the object: anemic domain object. Bad because of coupling

all constructor arguments should be valid and on each construction the object should be valid
on changeState only accept valid arguments

## Only valid values should cross object boundaries
object has a natural boundary; don't know how it works inside
pass in arguments, return values, exceptions (travel across boundary)
-> check pre-conditions: check only something valid enters the object
-> check post-conditions: check only something valid leaves the object

Easy to reason about
Easy to debug
Easy to refactor: all validation logic is inside this single object

## Object should be immutable
return on changeState return a copy of the object
-> clear distinction between state changing client and state reading client

## Objects should communicate using well-defined messages
message passing between objects. CQS:
Messages like: command (do), query (give), document (neutral)
command -> do without return message or exception
query -> give document

## Everything is an object
Application is an object too
Apply CQS: command / query rule


DIVIDE AND CONQUER (Andreas hucks)
----------------------------------
symphony = magic
BAD:
direct framework dependencies
direct access to entities

WHY BAD:
clean code, testable, maintainable,
framework can be easier upgraded
- separate your code from framework or 3rd parties
- separate your own layers: domain / application / infrastructure

## Extracting domain
- into own namespace
- difficult: tight coupling with ORM / AR.

- use own domain exceptions
- Deptrac tool: static analysis to find dependencies between packages
regex: ^\\App\\.*

## Bridge
Bridge: connect two components that should not rely on each other.
Component should be standalone and to integrate them with a fw you need a bridge
Build a \\Bridge namespace (e.g. Twig functions) 
use interfaces

    - Bridge
        - Doctrine
            - Mapping
            - Repository
    - Domain
        - Entity
        - Repository
        
Domain repository is now an interface. Bridge contains the implementation.

split user entity from FW User model
-> create MechanicAccount as the model in the Bridge ns. An artificial userinterface class.

## Validation, Form, ViewModels
avoid direct access to entities

## Sooooo:
create simple DTO for usage in views
Validation: object for specific Form: e.g. RobotUpdate.php with validation inside
self-validating model
bridge between layers with one level connection


Cache logged users
------------------
 
cache is not a solution for a slow application
cache is a solution for subtle scale and hits
reverse proxy: separate machine between your server and public

## session strategy
- avoid sessions if possible (cleanup cookies)
- move logic to frontend: change userspecific stuff afterwards. Userstate is communicated to js in cookie. Ajax calls can be cached!
- esi edge side includes (symfony supports this)
- usercontext
    - group based caching: guest ,admin, reader,...
    - computed for every user
    - hash lookup by cache proxy
    
    
Time Management
---------------

"getting stuff done"
"doing what you can with the time you have"

1. Work from finish to start
2. You can never create more time
3. Do fewer things but do them better

- open-ended tasks destroy productivity: create own deadlines if necessary
- put everything in your calendar: take control of your schedule so you don't overcommit
- break down tasks to the smallest bit
- don't be a hero (burnout)
- Leave room for mistakes
- don't overextend (take breaks)
- work in blocks (pauses for rewards)
- discipline

Caching
-------

failed cache should not fail app
2015: adapters

## PSR-6: Repository - entity
ItemPool - Item
call pool first: give me item with this key. 
- stampede protection: parallel incoming requests executing long process to update cache value
- taggable cache $item->addtags(), $pool->clearTags()
- hierarchical cache: $pool->delete('tree/*');
- // {}()/\@ are invalid key chars
- https://packagist.org/packages/cache/integration-tests
- PSR-6 caching decorator
- https://packagist.org/packages/madewithlove/illuminate-psr-cache-bridge

##  PSR-16: simple cache
- 1 interface: CacheInterface
- $cache->get($key); 
- getMultiple(), setMultiple(), deleteMultiple()
- returns null if not available
- so null as a value cannot be set. but you can store a Value Object instead: $cache->set($key, new ValueObject());
- no deferring. set happens immediately


STATE
-----

diagram: states and transitions
## Deterministic Finite automaton: 
for each state change there is only one possible transition
    - Acceptor
    - transducer
        - mealy: next state does not depend on current state?
        - more: next state depends on the current state
initial state - accepted state

avoid setting state everywhere like $order->setStatus(Order::STATUS_COMPLETED)
use the finite state acceptor pattern:
flatten state diagram (package window state machine)
[
    'create' => [
        'form => new,
        to => pending
    ]
    ...
]_

$stateMachine = $stateMachineFactory->get(Order);
$stateMachine->apply('complete');

Will throw exception if transition is invalid

Ask SM what to do based on current state:
$sm->can('complete') // false
$sm->can('refund') // true
$sm->can('cancel') // false

this makes it easy to hook events on these transitions: e.g. after refund transitions

## State pattern
Each state implements own behaviour

## example: checkout
details - payment - review

zend-expressive (psr-7 middleware microframework)
rskuipers/stateful-...

Shipment
ShipmentState
    - process()