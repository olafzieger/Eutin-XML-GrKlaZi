<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0" 
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
	xsi:noNamespaceSchemaLocation="PDFschema.xsd">
	
	<xsl:output method="xml" encoding="UTF-8" indent="no" />
	
	<xsl:template match="fields">
		<!-- TODO: Auto-generated template -->
		<fields xmlns:od="urn:schemas-microsoft-com:officedata"
			xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xsi:noNamespaceSchemaLocation="Journal_kurz.xsd">
			<xsl:element name="programm">
				<xsl:value-of select="programm" />
			</xsl:element>
			<xsl:element name="nummer">
				<xsl:value-of select="nummer" />
			</xsl:element>
			<xsl:element name="beschreibung">
				<xsl:value-of select="beschreibung" />
			</xsl:element>
			<xsl:element name="textabschnitt1">
				<xsl:element name="anbieter"><xsl:value-of select="anbieter" /><xsl:text>&#xD;</xsl:text></xsl:element>
				<xsl:element name="internet"><xsl:value-of select="internet" /></xsl:element>
			</xsl:element>
			<xsl:element name="textabschnitt2">
				<xsl:element name="hinweise"><xsl:text>Besonderheit:&#xD;</xsl:text></xsl:element>
				<xsl:element name="besonderheit"><xsl:value-of select="besonderheit" /></xsl:element>
				<xsl:text>&#xD;</xsl:text>
				<xsl:element name="hinweise"><xsl:text>Termine:&#xD;</xsl:text></xsl:element>
				<xsl:element name="termine"><xsl:value-of select="termine" /></xsl:element>
			</xsl:element>
			<xsl:element name="berufsschule">
				<xsl:value-of select="berufsschule" />
			</xsl:element>
			<xsl:element name="eins">
				<xsl:value-of select="eins" />
			</xsl:element>
			<xsl:element name="elf">
				<xsl:value-of select="elf" />
			</xsl:element>
			<xsl:element name="foerderschule">
				<xsl:value-of select="foerderschule" />
			</xsl:element>
			<xsl:element name="fuen">
				<xsl:value-of select="fuenf" />
			</xsl:element>
			<xsl:element name="kika">
				<xsl:value-of select="kika" />
			</xsl:element>
			<xsl:element name="lehrer">
				<xsl:value-of select="lehrer" />
			</xsl:element>
			<xsl:element name="optionsfeld3">
				<xsl:value-of select="Optionsfeld3" />
			</xsl:element>
		</fields>
	</xsl:template>
</xsl:stylesheet>