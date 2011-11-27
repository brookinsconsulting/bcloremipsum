<?php /* #?ini charset="utf-8"?

[BCLoremIpsumSettings]
# By default the bcloremipsum/create module view cache checks
# that the current user is a logged in user with a role of Administrator
# or a the kernel error, access denied is presented to the user.
CreateNodesChecksForUserRole=enabled
# Role ID of the specific role you wish to restrict access by
CreateNodesChecksForRoleID=2
# ClassID of class to create content tree nodes using. Default is 'article' ('identifier') class ID '16'
DefaultClassID=16
# Default number of nodes to create
DefaultCreateNodeCount=100
# Default node creation option is to enable the less resource intensive creation mode
DefaultCreateQuickMode=enabled
# Default string trim (removes spaces on sides of string and unix newlines between words)
DefaultCreateTrimStrings=enabled

# Default datatypes supported
DatatypeIdentifiersSupported[]
DatatypeIdentifiersSupported[]=eztime
DatatypeIdentifiersSupported[]=ezdate
DatatypeIdentifiersSupported[]=ezdatetime
DatatypeIdentifiersSupported[]=eztext
DatatypeIdentifiersSupported[]=ezstring
DatatypeIdentifiersSupported[]=ezxmltext
DatatypeIdentifiersSupported[]=ezboolean
DatatypeIdentifiersSupported[]=ezimage
DatatypeIdentifiersSupported[]=ezkeyword
DatatypeIdentifiersSupported[]=ezinteger
DatatypeIdentifiersSupported[]=ezfloat
DatatypeIdentifiersSupported[]=ezprice
DatatypeIdentifiersSupported[]=ezuser

# Default datatypes unsupported
DatatypeIdentifiersUnsupported[]
DatatypeIdentifiersUnsupported[]=ezauthor
DatatypeIdentifiersUnsupported[]=ezemail
DatatypeIdentifiersUnsupported[]=ezbinaryfile
DatatypeIdentifiersUnsupported[]=ezidentifier
DatatypeIdentifiersUnsupported[]=ezobjectrelationlist
DatatypeIdentifiersUnsupported[]=ezobjectrelation
DatatypeIdentifiersUnsupported[]=ezselection
DatatypeIdentifiersUnsupported[]=ezurl
DatatypeIdentifiersUnsupported[]=ezmatrix
DatatypeIdentifiersUnsupported[]=ezmedia
DatatypeIdentifiersUnsupported[]=ezisbn
DatatypeIdentifiersUnsupported[]=keywords
DatatypeIdentifiersUnsupported[]=ezsrrating
DatatypeIdentifiersUnsupported[]=ezgmaplocation
# Also any custom extension datatypes are also unsupported by default

# Default deprecated webshop datatypes unsupported
DatatypeIdentifiersDeprecatedUnsupported[]
DatatypeIdentifiersDeprecatedUnsupported[]=ezenum
DatatypeIdentifiersDeprecatedUnsupported[]=ezpackage
DatatypeIdentifiersDeprecatedUnsupported[]=ezinisetting
DatatypeIdentifiersDeprecatedUnsupported[]=ezmultioption
DatatypeIdentifiersDeprecatedUnsupported[]=ezsubtreesubscription

# Default deprecated general datatypes unsupported
DatatypeIdentifiersDeprecatedWebshopUnsupported[]
DatatypeIdentifiersDeprecatedWebshopUnsupported[]=ezoption
DatatypeIdentifiersDeprecatedWebshopUnsupported[]=ezcountry
DatatypeIdentifiersDeprecatedWebshopUnsupported[]=ezmultiprice
DatatypeIdentifiersDeprecatedWebshopUnsupported[]=ezrangeoption
DatatypeIdentifiersDeprecatedWebshopUnsupported[]=ezmultioption
DatatypeIdentifiersDeprecatedWebshopUnsupported[]=ezproductcategory

*/ ?>
