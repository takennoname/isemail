using System;
using System.Text;
using System.Collections.Generic;
using System.Linq;
using Microsoft.VisualStudio.TestTools.UnitTesting;

namespace IsEmailTests
{
    /// <summary>
    /// Summary description for UnitTest1
    /// </summary>
    [TestClass]
    public class UnitTests
    {
        public UnitTests()
        {
            //
            // TODO: Add constructor logic here
            //
        }

        private TestContext testContextInstance;

        /// <summary>
        ///Gets or sets the test context which provides
        ///information about and functionality for the current test run.
        ///</summary>
        public TestContext TestContext
        {
            get
            {
                return testContextInstance;
            }
            set
            {
                testContextInstance = value;
            }
        }

        #region Additional test attributes
        //
        // You can use the following additional attributes as you write your tests:
        //
        // Use ClassInitialize to run code before running the first test in the class
        // [ClassInitialize()]
        // public static void MyClassInitialize(TestContext testContext) { }
        //
        // Use ClassCleanup to run code after all tests in a class have run
        // [ClassCleanup()]
        // public static void MyClassCleanup() { }
        //
        // Use TestInitialize to run code before running each test 
        // [TestInitialize()]
        // public void MyTestInitialize() { }
        //
        // Use TestCleanup to run code after each test has run
        // [TestCleanup()]
        // public void MyTestCleanup() { }
        //
        #endregion

        
            //Assert.IsTrue(com.dominicsayers.isemail.IsEMail.IsEmailValid("sean@foo.bar.com"));
            //Assert.IsTrue(com.dominicsayers.isemail.IsEMail.IsEmailValid("sean+foo@foo.com"));
            //Assert.IsTrue(com.dominicsayers.isemail.IsEMail.IsEmailValid("sean@123.456.123.456"));
            //Assert.IsTrue(com.dominicsayers.isemail.IsEMail.IsEmailValid("sean@foo.com"));
            //Assert.IsTrue(com.dominicsayers.isemail.IsEMail.IsEmailValid("sean@foo.com"));

        [TestMethod]
        public void StandardEmail()
        {
            com.dominicsayers.isemail.IsEMail isEmail = new com.dominicsayers.isemail.IsEMail();
            Assert.IsTrue(isEmail.IsEmailValid("sean@foo.com"));
        }

        [TestMethod]
        public void PlusSign()
        {
            com.dominicsayers.isemail.IsEMail isEmail = new com.dominicsayers.isemail.IsEMail();
            Assert.IsTrue(isEmail.IsEmailValid("sean+foo@bar.com"));
        }
        
        [TestMethod]
        public void IPAddressDomain()
        {
            //http://swfmantis/view.php?id=21522
            com.dominicsayers.isemail.IsEMail isEmail = new com.dominicsayers.isemail.IsEMail();
            Assert.IsTrue(isEmail.IsEmailValid("test@66.194.75.4"));
        }

        [TestMethod]        
        public void DomainTooLong()
        {
            // http://swfmantis/view.php?id=21523
            com.dominicsayers.isemail.IsEMail isEmail = new com.dominicsayers.isemail.IsEMail();
            Assert.IsFalse(isEmail.IsEmailValid("test@1234567890123456789012345678901234567890123456789012345678901234.com"));
        }

        [TestMethod]
        public void MultipleSeperators()
        {
            // http://swfmantis/view.php?id=21524
            com.dominicsayers.isemail.IsEMail isEmail = new com.dominicsayers.isemail.IsEMail();
            Assert.IsFalse(isEmail.IsEmailValid("A@B@test.com"));
        }

        [TestMethod]
        public void SingleDoubleQuote()
        {
            // http://swfmantis/view.php?id=21521
            com.dominicsayers.isemail.IsEMail isEmail = new com.dominicsayers.isemail.IsEMail();
            Assert.IsFalse(isEmail.IsEmailValid("\"Wrong.Quotes@test.com"));
        }

        
        [TestMethod]
        public void LeadingPeriod()
        {
            // http://swfmantis/view.php?id=21518
            com.dominicsayers.isemail.IsEMail isEmail = new com.dominicsayers.isemail.IsEMail();
            Assert.IsFalse(isEmail.IsEmailValid(".Not-OK@test.com"));
        }

        [TestMethod]
        public void QuotedIllegals()
        {
            // http://swfmantis/view.php?id=21517
            com.dominicsayers.isemail.IsEMail isEmail = new com.dominicsayers.isemail.IsEMail();
            Assert.IsTrue(isEmail.IsEmailValid("\"Quotes;allowed\"@test.com"));
            Assert.IsTrue(isEmail.IsEmailValid("\"Quotes allowed\"@test.com"));
            Assert.IsTrue(isEmail.IsEmailValid("\"Quotes,allowed\"@test.com"));
            Assert.IsTrue(isEmail.IsEmailValid("\"Quotes\allowed\"@test.com"));
            Assert.IsTrue(isEmail.IsEmailValid("\"Quotes[allowed\"@test.com"));
            Assert.IsTrue(isEmail.IsEmailValid("\"Quotes]allowed\"@test.com"));
            Assert.IsTrue(isEmail.IsEmailValid("\"Quotes<allowed\"@test.com"));
            Assert.IsTrue(isEmail.IsEmailValid("\"Quotes>allowed\"@test.com"));
            Assert.IsTrue(isEmail.IsEmailValid("\"Quotes(allowed\"@test.com"));
            Assert.IsTrue(isEmail.IsEmailValid("\"Quotes)allowed\"@test.com"));
            Assert.IsTrue(isEmail.IsEmailValid("\"Quotes:allowed\"@test.com"));
        }

        [TestMethod]
        public void LocalPartTooLong()
        {
            // http://swfmantis/view.php?id=21513
            com.dominicsayers.isemail.IsEMail isEmail = new com.dominicsayers.isemail.IsEMail();
            Assert.IsFalse(isEmail.IsEmailValid("ThisIsAnInvalidEmailAddressThatIsSixtyFiveCharactersTooLongByaCHA@test.com"));
        }

        [TestMethod]
        public void UnQuotedIllegals()
        {
            // http://swfmantis/view.php?id=21515
            com.dominicsayers.isemail.IsEMail isEmail = new com.dominicsayers.isemail.IsEMail();
            Assert.IsFalse(isEmail.IsEmailValid("QuotesNot;allowed@test.com"));
            Assert.IsFalse(isEmail.IsEmailValid("QuotesNot allowed@test.com"));
            Assert.IsFalse(isEmail.IsEmailValid("QuotesNot,allowed@test.com"));
            Assert.IsFalse(isEmail.IsEmailValid("QuotesNot\allowed@test.com"));
            Assert.IsFalse(isEmail.IsEmailValid("QuotesNot[allowed@test.com"));
            Assert.IsFalse(isEmail.IsEmailValid("QuotesNot]allowed@test.com"));
            Assert.IsFalse(isEmail.IsEmailValid("QuotesNot<allowed@test.com"));
            Assert.IsFalse(isEmail.IsEmailValid("QuotesNot>allowed@test.com"));
            Assert.IsFalse(isEmail.IsEmailValid("QuotesNot(allowed@test.com"));
            Assert.IsFalse(isEmail.IsEmailValid("QuotesNot)allowed@test.com"));
            Assert.IsFalse(isEmail.IsEmailValid("QuotesNot:allowed@test.com"));
        }

        [TestMethod]
        public void EntireEmailTooLong()
        {
            // http://swfmantis/view.php?id=21514
            com.dominicsayers.isemail.IsEMail isEmail = new com.dominicsayers.isemail.IsEMail();
            Assert.IsFalse(isEmail.IsEmailValid("ab@Label100000000000000000000000000000000000000000000000000000000X.Label200000000000000000000000000000000000000000000000000000000X.Label300000000000000000000000000000000000000000000000000000000X.Label10000000000000000000000000000000000000000000000000X.com"));
        }       

        [TestMethod]
        public void ipv6eMAIL()
        {
            // http://swfmantis/view.php?id=21514
            com.dominicsayers.isemail.IsEMail isEmail = new com.dominicsayers.isemail.IsEMail();
            Assert.IsFalse(isEmail.IsEmailValid("SEAN@2607:f0d0:1002:51::4"));
        }        
    }
}
