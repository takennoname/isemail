using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using com.dominicsayers.isemail;
using System.Windows.Forms;

namespace IsEmailTester
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            IsEMail isEmail = new IsEMail();
            bool result = isEmail.IsEmailValid(this.textBox1.Text);

            if (result)
            {
                this.label1.Text = "'" + this.textBox1.Text + "' is a valid email";
            }
            else
            {
                this.label1.Text = "'" + this.textBox1.Text + "' is NOT a valid email!" + Environment.NewLine;

                foreach (string s in isEmail.ResultInfo)
                {
                    this.label1.Text += s + Environment.NewLine;
                }
            }
        }
    }
}
